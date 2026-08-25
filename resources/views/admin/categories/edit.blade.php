@extends('layouts.admin')

@section('content')
<div class="page-header">
    <a href="/admin/categories" class="back-link">← Back to Categories</a>
</div>

@php
$acceptString = implode(',', array_map(function($ext) { return 'image/' . trim($ext); }, explode(',', $supportedImageFormats ?? 'jpeg,jpg,png,webp')));
$maxMb = round(($maxImageSize ?? 2048) / 1024, 1);
$displayFormats = strtoupper(str_replace(',', ', ', $supportedImageFormats ?? 'jpeg, jpg, png, webp'));
@endphp

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

<form action="/admin/categories/{{ $category->id }}" method="POST" enctype="multipart/form-data" class="product-form">
    @csrf
    @method('PUT')

    <div class="form-grid">
        <div class="form-main">
            <div class="panel">
                <div class="panel-header"><h3>Category Information</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
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
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        </div>
                        <div class="form-group">
                            <label class="toggle-label" style="margin-top:1.5rem">
                                <input type="checkbox" name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
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
                    <div class="form-group" x-data="{ preview: '{{ $category->image ?? '' }}' }">
                        @if ($category->image)
                        <div class="current-image">
                            <img src="{{ !empty($category->image) ? asset('storage/' . $category->image) : '' }}" alt="Current image">
                        </div>
                        @endif
                        <div class="image-upload" @click="$refs.fileInput.click()">
                            <div class="upload-placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg>
                                <span>Upload new image</span>
                                <small>{{ $displayFormats }} (max {{ $maxMb }}MB)</small>
                            </div>
                        </div>
                        <input type="file" name="image_file" x-ref="fileInput" accept="{{ $acceptString }}" style="display:none">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-primary-admin btn-full">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path></svg>
                Update Category
            </button>
        </div>
    </div>
</form>
@endsection
