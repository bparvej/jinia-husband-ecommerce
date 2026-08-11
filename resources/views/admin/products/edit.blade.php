@extends('layouts.admin')

@section('content')
<div class="page-header">
    <a href="/admin/products" class="back-link">← Back to Products</a>
</div>

@if (!empty($error))
<div class="alert alert-error">{{ $error }}</div>
@endif

@if ($errors->any())
<div class="alert alert-error">
    <ul style="margin: 0; padding-left: 1.5rem;">
        @foreach ($errors->all() as $validationError)
            <li>{{ $validationError }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="product-form">
    @csrf
    @method('PUT')

    <div class="form-grid">
        <div class="form-main">
            <div class="panel">
                <div class="panel-header"><h3>Product Information</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" maxlength="500">
                    </div>
                    <div class="form-group">
                        <label for="description">Full Description</label>
                        <textarea id="description" name="description" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
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
                            <label for="compare_price">Compare Price (৳)</label>
                            <input type="number" id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="cost_price">Cost Price (৳)</label>
                            <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" step="0.01" min="0">
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
                            <input type="checkbox" name="is_active" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
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
                        <label for="sku">SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}">
                    </div>
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
                <div class="panel-header"><h3>Inventory</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->inventory->quantity ?? 0) }}" min="0">
                        <small class="text-muted">Set the new on-hand quantity. A ledger adjustment will be recorded.</small>
                    </div>
                    <div class="form-group">
                        <label for="low_stock_threshold">Low Stock Alert At</label>
                        <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->inventory->low_stock_threshold ?? 10) }}" min="0">
                    </div>
                    <div class="form-group">
                        <label for="warehouse_location">Warehouse Location</label>
                        <input type="text" id="warehouse_location" name="warehouse_location" value="{{ old('warehouse_location', $product->inventory->warehouse_location ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Main Image</h3></div>
                <div class="panel-body">
                    <div class="form-group" x-data="{ preview: '{{ $product->image ?? '' }}' }">
                        @if ($product->image)
                        <div class="current-image">
                            <img src="{{ $product->image }}" alt="Current image">
                        </div>
                        @endif
                        <div class="image-upload" @click="$refs.fileInput.click()">
                            <div class="upload-placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg>
                                <span>Upload new image</span>
                            </div>
                        </div>
                        <input type="file" name="image_file" x-ref="fileInput" accept="image/jpeg,image/png,image/webp" style="display:none">
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Gallery Images</h3></div>
                <div class="panel-body">
                    <div class="form-group" x-data="{
                        existing: {{ json_encode($product->images ?? []) }},
                        newPreviews: [],
                        removeIndex: null,
                        get allExisting() { return this.existing.map((src, i) => ({ src, id: i, isExisting: true })) },
                        get allNew() { return this.newPreviews.map((src, i) => ({ src, id: i, isExisting: false })) },
                        removeExisting(i) { this.existing.splice(i, 1); this.$refs.removedInput.value = JSON.stringify(this.existing) }
                    }">
                        <div class="gallery-upload">
                            <template x-if="existing.length || newPreviews.length">
                                <div class="gallery-thumbs">
                                    <template x-for="(item, i) in [...allExisting, ...allNew]" :key="item.id">
                                        <div class="gallery-thumb">
                                            <img :src="item.src" alt="Gallery image">
                                            <button type="button" class="gallery-remove"
                                                @click="item.isExisting ? removeExisting(item.id) : (newPreviews = newPreviews.filter((_, idx) => idx !== item.id))">&times;</button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div class="image-upload" @click="$refs.fileInput.click()">
                                <div class="upload-placeholder upload-placeholder-sm">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    <span>Add gallery images</span>
                                    <small>JPEG, PNG, WebP — select multiple</small>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="existing_images" x-ref="removedInput" :value="JSON.stringify(existing)">
                        <input type="file" name="gallery_images[]" x-ref="fileInput" accept="image/jpeg,image/png,image/webp" multiple style="display:none"
                            @change="newPreviews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-primary-admin btn-full">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path></svg>
                Update Product
            </button>
        </div>
    </div>
</form>
@endsection
