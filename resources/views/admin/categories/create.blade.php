@extends('layouts.admin')

@section('content')
<div class="page-header">
    <a href="/admin/categories" class="back-link">← Back to Categories</a>
</div>

@if ($error)
<div class="alert alert-error">{{ $error }}</div>
@endif

<form action="/admin/categories" method="POST" enctype="multipart/form-data" class="product-form">
    @csrf

    <div class="form-grid">
        <div class="form-main">
            <div class="panel">
                <div class="panel-header"><h3>Category Information</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required placeholder="e.g. Lighting">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Brief description of this category">{{ old('description', $category->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>Parent & Ordering</h3></div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select id="parent_id" name="parent_id">
                                <option value="">None (Top Level)</option>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? '0') }}" min="0">
                        </div>
                        <div class="form-group">
                            <label class="toggle-label" style="margin-top:1.5rem">
                                <input type="checkbox" name="is_active" {{ old('is_active', $category->is_active ?? true) !== false ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-side">
            <div class="panel">
                <div class="panel-header"><h3>Category Image</h3></div>
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
                                    <small>JPEG, PNG, WebP (max 2MB)</small>
                                </div>
                            </template>
                        </div>
                        <input type="file" name="image_file" x-ref="fileInput" accept="image/*" style="display:none"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-primary-admin btn-full">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Create Category
            </button>
        </div>
    </div>
</form>
@endsection
