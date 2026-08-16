@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h2 class="page-title">Settings</h2>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
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

<div class="form-grid">
    <div class="form-main">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="product-form">
            @csrf
            
            <div class="panel">
                <div class="panel-header"><h3>Image Upload Settings</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="max_image_size">Max Image Size (KB)</label>
                        <input type="number" id="max_image_size" name="max_image_size" value="{{ old('max_image_size', $maxImageSize) }}" required min="100" max="51200" placeholder="e.g. 2048 for 2MB">
                        <small>Specify the maximum allowed image file size in kilobytes. (e.g. 2048 = 2MB, 5120 = 5MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="supported_image_formats">Supported Image Formats</label>
                        <input type="text" id="supported_image_formats" name="supported_image_formats" value="{{ old('supported_image_formats', $supportedImageFormats) }}" required placeholder="e.g. jpeg,jpg,png,webp">
                        <small>Comma-separated list of allowed file extensions.</small>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <button type="submit" class="btn-admin btn-primary-admin">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
