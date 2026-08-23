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
        $maxImageSize = \App\Models\Setting::get('max_image_size', '2048');
        $supportedImageFormats = \App\Models\Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

        return view('admin.categories.create', [
            'categories' => $categories,
            'category' => null,
            'error' => null,
            'maxImageSize' => $maxImageSize,
            'supportedImageFormats' => $supportedImageFormats,
            'title' => 'Add Category — HomeI Admin'
        ]);
    }

    public function adminStore(Request $request)
    {
        try {
            $maxImageSize = \App\Models\Setting::get('max_image_size', '2048');
            $supportedImageFormats = \App\Models\Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

            $request->validate([
                'name' => 'required|string|max:100',
                'image_file' => 'nullable|image|mimes:' . $supportedImageFormats . '|max:' . $maxImageSize
            ], [
                'image_file.image' => 'The file must be an image.',
                'image_file.max' => 'The image must not be larger than ' . round($maxImageSize / 1024, 1) . 'MB.',
                'image_file.mimes' => 'The image must be a file of type: ' . $supportedImageFormats . '.',
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
                $this->ensureDirectory(public_path('uploads/categories'));
                $file->move(public_path('uploads/categories'), $filename);
                $data['image'] = '/uploads/categories/' . $filename;
            }

            Category::create($data);

            Log::info("Category created: " . $request->input('name'));

            if ($request->headers->has('hx-request')) {
                return response('')->header('HX-Redirect', '/admin/categories');
            }

            return redirect('/admin/categories');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
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
        $maxImageSize = \App\Models\Setting::get('max_image_size', '2048');
        $supportedImageFormats = \App\Models\Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

        return view('admin.categories.edit', [
            'category' => $category,
            'categories' => $categories,
            'error' => null,
            'maxImageSize' => $maxImageSize,
            'supportedImageFormats' => $supportedImageFormats,
            'title' => 'Edit ' . $category->name . ' — HomeI Admin'
        ]);
    }

    public function adminUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        try {
            $maxImageSize = \App\Models\Setting::get('max_image_size', '2048');
            $supportedImageFormats = \App\Models\Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

            $request->validate([
                'name' => 'required|string|max:100',
                'image_file' => 'nullable|image|mimes:' . $supportedImageFormats . '|max:' . $maxImageSize
            ], [
                'image_file.image' => 'The file must be an image.',
                'image_file.max' => 'The image must not be larger than ' . round($maxImageSize / 1024, 1) . 'MB.',
                'image_file.mimes' => 'The image must be a file of type: ' . $supportedImageFormats . '.',
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
                $this->ensureDirectory(public_path('uploads/categories'));
                $file->move(public_path('uploads/categories'), $filename);
                $data['image'] = '/uploads/categories/' . $filename;
            }

            $category->update($data);

            Log::info("Category updated: " . $category->name);

            if ($request->headers->has('hx-request')) {
                return response('')->header('HX-Redirect', '/admin/categories');
            }

            return redirect('/admin/categories');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
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

    /**
     * Make sure an upload directory exists before moving a file into it.
     */
    private function ensureDirectory(string $directory): void
    {
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new \Exception("Failed to create upload directory: " . $directory);
        }
    }
}
