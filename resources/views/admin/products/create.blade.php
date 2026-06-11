@extends('layouts.admin')

@section('content')
<div class="page-header">
    <a href="/admin/products" class="back-link">← Back to Products</a>
</div>

@if ($error)
<div class="alert alert-error">{{ $error }}</div>
@endif

<form action="/admin/products" method="POST" enctype="multipart/form-data" class="product-form">
    @csrf

    <div class="form-grid">
        <div class="form-main">
            <div class="panel">
                <div class="panel-header"><h3>Product Information</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required placeholder="e.g. Nordic Oak Ladder Shelf">
                    </div>
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" placeholder="Brief product summary" maxlength="500">
                    </div>
                    <div class="form-group">
                        <label for="description">Full Description</label>
                        <textarea id="description" name="description" rows="5" placeholder="Detailed product description">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Pricing</h3></div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (৳) *</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" required step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="compare_price">Compare at Price (৳)</label>
                            <input type="number" id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="cost_price">Cost Price (৳)</label>
                            <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Inventory</h3></div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="e.g. HI-BS-001">
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? '0') }}" min="0">
                        </div>
                        <div class="form-group">
                            <label for="low_stock_threshold">Low Stock Alert At</label>
                            <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? '10') }}" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-side">
            <div class="panel">
                <div class="panel-header"><h3>Status</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="toggle-label">
                            <input type="checkbox" name="is_active" {{ old('is_active', $product->is_active ?? true) !== false ? 'checked' : '' }}>
                            <span>Active</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="toggle-label">
                            <input type="checkbox" name="is_featured" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                            <span>Featured</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Organization</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id">
                            <option value="">Select category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="badge">Badge</label>
                        <select id="badge" name="badge">
                            <option value="">None</option>
                            <option value="New" {{ old('badge', $product->badge ?? '') === 'New' ? 'selected' : '' }}>New</option>
                            <option value="Hot" {{ old('badge', $product->badge ?? '') === 'Hot' ? 'selected' : '' }}>Hot</option>
                            <option value="Sale" {{ old('badge', $product->badge ?? '') === 'Sale' ? 'selected' : '' }}>Sale</option>
                            <option value="Best Seller" {{ old('badge', $product->badge ?? '') === 'Best Seller' ? 'selected' : '' }}>Best Seller</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Product Image</h3></div>
                <div class="panel-body">
                    <div class="form-group" x-data="{ preview: null }">
                        <div class="image-upload" @click="$refs.fileInput.click()">
                            <template x-if="preview">
                                <img :src="preview" alt="Preview" class="upload-preview">
                            </template>
                            <template x-if="!preview">
                                <div class="upload-placeholder">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    <span>Click to upload image</span>
                                    <small>JPEG, PNG, WebP (max 5MB)</small>
                                </div>
                            </template>
                        </div>
                        <input type="file" name="image" x-ref="fileInput" accept="image/*" style="display:none"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-primary-admin btn-full">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Create Product
            </button>
        </div>
    </div>
</form>
@endsection
