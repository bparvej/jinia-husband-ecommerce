<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // --- ADMIN: Product Listing ---
    public function adminIndex(Request $request)
    {
        $query = Product::with('category');

        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status !== null && $status !== '') {
            $isActive = $status === 'active' || $status === '1' || $status === 'true';
            $query->where('is_active', $isActive);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Category::all();

        $viewData = [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category_id' => $categoryId,
                'status' => $status
            ],
            'title' => 'Products — HomeI Admin'
        ];

        // HTMX request partial return
        if ($request->headers->has('hx-request')) {
            return view('admin.products.partials.product-table', $viewData);
        }

        return view('admin.products.index', $viewData);
    }

    // --- ADMIN: Create Product Form ---
    public function adminCreate()
    {
        $categories = Category::all();
        return view('admin.products.create', [
            'categories' => $categories,
            'product' => null,
            'error' => null,
            'title' => 'Add Product — HomeI Admin'
        ]);
    }

    // --- ADMIN: Store Product ---
    public function adminStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|regex:/^[\p{L}\s\-.\'0-9]+$/u|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
                'slug' => 'nullable|string|max:280|unique:products,slug|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'description' => 'nullable|string|max:65535|not_regex:/^(test_|invalid_|dummy_|sample_).{100,}$/i',
                'short_description' => 'nullable|string|max:500|not_regex:/^(test_|invalid_|dummy_|sample_).{150,}$/i',
                'price' => 'required|numeric|min:0|max:999999999.99|not_regex:/^(0\.00|0\.01|0\.99|test_\d+\.\d+)$/i',
                'compare_price' => 'nullable|numeric|min:0|max:999999999.99|not_regex:/^(0\.00|0\.01|0\.99|test_\d+\.\d+)$/i',
                'cost_price' => 'nullable|numeric|min:0|max:999999999.99|not_regex:/^(0\.00|0\.01|0\.99|test_\d+\.\d+)$/i',
                'sku' => 'nullable|string|max:100|unique:products,sku|regex:/^[A-Z0-9\-_]+$/i|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
                'category_id' => 'nullable|integer|exists:categories,id|not_regex:/^test_/i',
                'image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048|dimensions:max_width=2500,max_height=2500,min_width=200,min_height=200',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048|dimensions:max_width=2500,max_height=2500',
                'badge' => 'nullable|string|max:50|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
                'is_active' => 'required|boolean',
                'is_featured' => 'required|boolean',
                'stock_quantity' => 'required|integer|min:0|max:9999|not_regex:/^test_/i',
                'low_stock_threshold' => 'required|integer|min:1|max:100|not_regex:/^test_/i',
                'warehouse_location' => 'nullable|string|max:100|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
            ]);

            $slug = Str::slug($request->input('name'));
            // Ensure slug is unique
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            // Server-side validation using product model
            //$validationErrors = $product->validateProductData($request->all(), $operation: 'update');
            $validationErrors = $product->validateProductData(
                                    data: $request->all(),
                                    operation: 'update'
                                );
            if (!empty($validationErrors)) {
                $errorMessages = [];
                foreach ($validationErrors as $field => $errors) {
                    $errorMessages[] = implode(', ', $errors);
                }
                
                $errorMessage = "Validation failed: " . implode('; ', $errorMessages);
                
                Log::warning("Product update validation error: " . $errorMessage . " for product ID: " . $id . ", User ID: " . auth()->id());
                
                if ($request->headers->has('hx-request')) {
                    return response()->json(['errors' => $validationErrors, 'message' => $errorMessage], 422);
                }
                
                $categories = Category::all();
                return view('admin.products.edit', [
                    'product' => $product,
                    'categories' => $categories,
                    'error' => $errorMessage,
                    'title' => 'Edit ' . $product->name . ' — HomeI Admin'
                ]);
            }
            
            // Validate product data on server-side
            //$validationErrors = Product::validateProductData($request->all(), $operation: 'store');
            $validationErrors = Product::validateProductData(
                                    data: $request->all(),
                                    operation: 'store'
                                );
            if (!empty($validationErrors)) {
                $errorMessages = [];
                foreach ($validationErrors as $field => $errors) {
                    $errorMessages[] = implode(', ', $errors);
                }
                
                $errorMessage = "Validation failed: " . implode('; ', $errorMessages);
                
                Log::warning("Product creation validation error: " . $errorMessage . ", User ID: " . auth()->id());
                
                if ($request->headers->has('hx-request')) {
                    return response()->json(['errors' => $validationErrors, 'message' => $errorMessage], 422);
                }
                
                $categories = Category::all();
                return view('admin.products.create', [
                    'categories' => $categories,
                    'product' => (object) $request->all(),
                    'error' => $errorMessage,
                    'title' => 'Add Product — HomeI Admin'
                ]);
            }
            
            $productData = [
                'name' => $request->input('name'),
                'slug' => $slug,
                'description' => $request->input('description'),
                'short_description' => $request->input('short_description'),
                'price' => floatval($request->input('price')),
                'compare_price' => $request->input('compare_price') ? floatval($request->input('compare_price')) : null,
                'cost_price' => $request->input('cost_price') ? floatval($request->input('cost_price')) : null,
                'sku' => $request->input('sku'),
                'category_id' => $request->input('category_id') ? intval($request->input('category_id')) : null,
                'badge' => $request->input('badge'),
                'is_active' => $request->input('is_active') === 'on' || $request->input('is_active') === 'true' || $request->input('is_active') === '1',
                'is_featured' => $request->input('is_featured') === 'on' || $request->input('is_featured') === 'true' || $request->input('is_featured') === '1',
            ];

            // Image file upload
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $productData['image'] = '/uploads/products/' . $filename;
            }

            // Gallery images upload
            $galleryImages = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/products/gallery'), $filename);
                    $galleryImages[] = '/uploads/products/gallery/' . $filename;
                }
            }
            $productData['images'] = $galleryImages;

            $product = Product::create($productData);

            // Create inventory entry
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => intval($request->input('stock_quantity', 0)),
                'low_stock_threshold' => intval($request->input('low_stock_threshold', 10)),
                'warehouse_location' => 'Dhaka Main'
            ]);

            Log::info("Product created: " . $product->name);

            if ($request->headers->has('hx-request')) {
                return response('')->header('HX-Redirect', '/admin/products');
            }

            return redirect('/admin/products');

        } catch (Exception $e) {
            $categories = Category::all();
            return view('admin.products.create', [
                'categories' => $categories,
                'product' => (object) $request->all(),
                'error' => $e->getMessage(),
                'title' => 'Add Product — HomeI Admin'
            ]);
        }
    }

    // --- ADMIN: Edit Product Form ---
    public function adminEdit($id)
    {
        $product = Product::with('inventory')->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'error' => null,
            'title' => 'Edit ' . $product->name . ' — HomeI Admin'
        ]);
    }

    // --- ADMIN: Update Product ---
    public function adminUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        try {
            $request->validate([
                //'name' => "required|string|max:255|regex:/^[\p{L}\s.\-'0-9]+$/u|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i",
                'name' => [
                            'required',
                            'string',
                            'max:255'
                            ],
                'slug' => 'nullable|string|max:280|unique:products,slug,' . $id . '|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'description' => 'nullable|string|max:65535|not_regex:/^(test_|invalid_|dummy_|sample_).{100,}$/i',
                'short_description' => 'nullable|string|max:500|not_regex:/^(test_|invalid_|dummy_|sample_).{150,}$/i',
                'price' => 'required|numeric|min:0|max:999999999.99|not_regex:/^(0\.00|0\.01|0\.99|test_\d+\.\d+)$/i',
                'compare_price' => 'nullable|numeric|min:0|max:999999999.99|not_regex:/^(0\.00|0\.01|0\.99|test_\d+\.\d+)$/i',
                'cost_price' => 'nullable|numeric|min:0|max:999999999.99|not_regex:/^(0\.00|0\.01|0\.99|test_\d+\.\d+)$/i',
                'sku' => 'nullable|string|max:100|unique:products,sku,' . $id . '|regex:/^[A-Z0-9\-_]+$/i|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
                'category_id' => 'nullable|integer|exists:categories,id|not_regex:/^test_/i',
                'image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048|dimensions:max_width=2500,max_height=2500,min_width=200,min_height=200',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048|dimensions:max_width=2500,max_height=2500',
                'badge' => 'nullable|string|max:50|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
                'is_active' => 'required|boolean',
                'is_featured' => 'required|boolean',
                'stock_quantity' => 'required|integer|min:0|max:9999',
                'low_stock_threshold' => 'required|integer|min:1|max:100',
                'warehouse_location' => 'nullable|string|max:100|not_regex:/^(test_|invalid_|dummy_|sample_).*$/i',
                'existing_images' => 'nullable|string',
                'existing_images.*' => 'nullable|string|max:500',
            ]);

            $productData = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'short_description' => $request->input('short_description'),
                'price' => floatval($request->input('price')),
                'compare_price' => $request->input('compare_price') ? floatval($request->input('compare_price')) : null,
                'cost_price' => $request->input('cost_price') ? floatval($request->input('cost_price')) : null,
                'sku' => $request->input('sku'),
                'category_id' => $request->input('category_id') ? intval($request->input('category_id')) : null,
                'badge' => $request->input('badge'),
                'is_active' => $request->input('is_active') === 'on' || $request->input('is_active') === 'true' || $request->input('is_active') === '1',
                'is_featured' => $request->input('is_featured') === 'on' || $request->input('is_featured') === 'true' || $request->input('is_featured') === '1',
            ];

            // Image file upload
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $productData['image'] = '/uploads/products/' . $filename;
            }

            // Gallery images handling
            $existingImages = [];
            if ($request->input('existing_images')) {
                $existingImages = json_decode($request->input('existing_images'), true);
                $existingImages = is_array($existingImages) ? $existingImages : [];
            } else {
                $existingImages = $product->images ?? [];
            }

            $newImages = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/products/gallery'), $filename);
                    $newImages[] = '/uploads/products/gallery/' . $filename;
                }
            }
            $productData['images'] = array_merge($existingImages, $newImages);

            $product->update($productData);

            Log::info("Product updated: " . $product->name);

            if ($request->headers->has('hx-request')) {
                return response('')->header('HX-Redirect', '/admin/products');
            }

            return redirect('/admin/products');

        } catch (Exception $e) {
            $categories = Category::all();
            return view('admin.products.edit', [
                'product' => $product,
                'categories' => $categories,
                'error' => $e->getMessage(),
                'title' => 'Edit ' . $product->name . ' — HomeI Admin'
            ]);
        }
    }

    // --- ADMIN: Delete Product ---
    public function adminDelete(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            Log::info("Product deleted: " . $product->name);

            if ($request->headers->has('hx-request')) {
                return response('');
            }

            return redirect('/admin/products');
        } catch (\Exception $e) {
            Log::error("Failed to delete product: " . $e->getMessage());
            return redirect('/admin/products')->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
