<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function adminIndex(Request $request)
    {
        $query = Category::withCount('products');

        $search = $request->query('search');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $categories = $query->orderBy('sort_order', 'asc')->paginate(12);

        $viewData = [
            'categories' => $categories,
            'filters' => ['search' => $search],
            'title' => 'Categories — HomeI Admin'
        ];

        if ($request->headers->has('hx-request')) {
            return view('admin.categories.partials.category-table', $viewData);
        }

        return view('admin.categories.index', $viewData);
    }

    public function adminCreate()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.create', [
            'categories' => $categories,
            'category' => null,
            'error' => null,
            'title' => 'Add Category — HomeI Admin'
        ]);
    }

    public function adminStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'image_file' => 'nullable|image|max:2048'
            ]);

            $slug = Str::slug($request->input('name'));
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $data = [
                'name' => $request->input('name'),
                'slug' => $slug,
                'description' => $request->input('description'),
                'parent_id' => $request->input('parent_id') ? intval($request->input('parent_id')) : null,
                'sort_order' => intval($request->input('sort_order', 0)),
                'is_active' => $request->input('is_active') === 'on' || $request->input('is_active') === 'true' || $request->input('is_active') === '1',
            ];

            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = 'cat_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/categories'), $filename);
                $data['image'] = '/uploads/categories/' . $filename;
            }

            Category::create($data);

            Log::info("Category created: " . $request->input('name'));

            if ($request->headers->has('hx-request')) {
                return response('')->header('HX-Redirect', '/admin/categories');
            }

            return redirect('/admin/categories');

        } catch (\Exception $e) {
            $categories = Category::orderBy('name')->get();
            return view('admin.categories.create', [
                'categories' => $categories,
                'category' => (object) $request->all(),
                'error' => $e->getMessage(),
                'title' => 'Add Category — HomeI Admin'
            ]);
        }
    }

    public function adminEdit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->orderBy('name')->get();

        return view('admin.categories.edit', [
            'category' => $category,
            'categories' => $categories,
            'error' => null,
            'title' => 'Edit ' . $category->name . ' — HomeI Admin'
        ]);
    }

    public function adminUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'image_file' => 'nullable|image|max:2048'
            ]);

            $data = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'parent_id' => $request->input('parent_id') ? intval($request->input('parent_id')) : null,
                'sort_order' => intval($request->input('sort_order', 0)),
                'is_active' => $request->input('is_active') === 'on' || $request->input('is_active') === 'true' || $request->input('is_active') === '1',
            ];

            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = 'cat_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/categories'), $filename);
                $data['image'] = '/uploads/categories/' . $filename;
            }

            $category->update($data);

            Log::info("Category updated: " . $category->name);

            if ($request->headers->has('hx-request')) {
                return response('')->header('HX-Redirect', '/admin/categories');
            }

            return redirect('/admin/categories');

        } catch (\Exception $e) {
            $categories = Category::where('id', '!=', $id)->orderBy('name')->get();
            return view('admin.categories.edit', [
                'category' => $category,
                'categories' => $categories,
                'error' => $e->getMessage(),
                'title' => 'Edit ' . $category->name . ' — HomeI Admin'
            ]);
        }
    }

    public function adminDelete(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            // Reassign products to no category
            \App\Models\Product::where('category_id', $id)->update(['category_id' => null]);

            $category->delete();

            Log::info("Category deleted: " . $category->name);

            if ($request->headers->has('hx-request')) {
                return response('');
            }

            return redirect('/admin/categories');
        } catch (\Exception $e) {
            Log::error("Failed to delete category: " . $e->getMessage());
            return redirect('/admin/categories')->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
