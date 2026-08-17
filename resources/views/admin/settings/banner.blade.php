@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h2 class="page-title">Banner Settings</h2>
</div>

@if (session('success'))
<div class="alert alert-success">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-error">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-error">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    <ul style="margin: 0; padding-left: 1.5rem; margin-top: 0.5rem;">
        @foreach ($errors->all() as $validationError)
            <li>{{ $validationError }}</li>
        @endforeach
    </ul>
</div>
@endif

@php
$acceptString = implode(',', array_map(function($ext) { return 'image/' . trim($ext); }, explode(',', $supportedImageFormats ?? 'jpeg,jpg,png,webp')));
$maxMb = round(($maxImageSize ?? 2048) / 1024, 1);
$displayFormats = strtoupper(str_replace(',', ', ', $supportedImageFormats ?? 'jpeg, jpg, png, webp'));
$isDefault = ($bannerImage ?? '') === '/assets/images/hero-living-room.png';
@endphp

<div class="form-grid">
    <div class="form-main" style="max-width: 800px;">
        <form action="{{ route('admin.settings.banner.update') }}" method="POST" enctype="multipart/form-data" class="product-form">
            @csrf

            <div class="panel">
                <div class="panel-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        Homepage Banner Image
                    </h3>
                </div>
                <div class="panel-body">

                    <div class="form-group" x-data="{ preview: null }">
                        <label style="font-weight: 600; font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">Current Banner</label>
                        <p class="text-muted" style="margin-top: 0.25rem; margin-bottom: 1rem; font-size: 0.9rem;">
                            This image is shown at the top of your storefront homepage.
                        </p>

                        <div class="image-upload" @click="$refs.fileInput.click()">
                            <template x-if="preview">
                                <img :src="preview" alt="New banner preview" class="upload-preview">
                            </template>
                            <template x-if="!preview">
                                <img src="{{ $bannerImage }}" alt="Current banner" class="upload-preview" style="display: block; margin: 0 auto;">
                            </template>
                        </div>

                        <input type="file" name="banner_image" x-ref="fileInput" accept="{{ $acceptString }}" style="display:none"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </div>

                    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">

                    <!-- Upload Instructions -->
                    <div style="background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 600; color: #111827; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            How to upload a good banner image
                        </div>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem; color: #4b5563; line-height: 1.7;">
                            <li>Use a <strong>wide, landscape</strong> image for the best look. A recommended size is <strong>1920 &times; 1080</strong> (16:9).</li>
                            <li>Allowed formats: <strong>{{ $displayFormats }}</strong>.</li>
                            <li>Maximum file size: <strong>{{ $maxMb }} MB</strong>.</li>
                            <li>Avoid text-heavy designs — your banner is displayed with an overlay and headline text.</li>
                            <li>The default banner is always shown until you upload a new one.</li>
                        </ul>
                    </div>

                    <div style="margin-top: 2.5rem; display: flex; gap: 1rem; align-items: center;">
                        <button type="submit" class="btn-admin btn-primary-admin" style="padding: 0.6rem 1.5rem; font-size: 1rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Upload Banner
                        </button>

                        <a href="{{ route('admin.dashboard') }}" class="btn-admin" style="background-color: transparent; border: 1px solid #d1d5db; color: #4b5563; padding: 0.6rem 1.5rem; font-size: 1rem; text-decoration: none; border-radius: 6px;">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        @if (!$isDefault)
        <div class="panel" style="border-color: #FECACA;">
            <div class="panel-header">
                <h3 style="color: #B91C1C;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;"><path d="M3 12l9-9 9 9"></path><path d="M5 10v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10"></path></svg>
                    Reset to Default
                </h3>
            </div>
            <div class="panel-body">
                <p class="text-muted" style="font-size: 0.9rem; margin-top: 0;">
                    Restore the original HomeI banner image. This removes your custom upload and shows the default banner on the homepage.
                </p>
                <form action="{{ route('admin.settings.banner.reset') }}" method="POST" style="margin-top: 1rem;"
                    onsubmit="return confirm('Reset the banner to the default image? Your custom banner will be removed.');">
                    @csrf
                    <button type="submit" class="btn-admin" style="background-color: #DC2626; border: none; color: #fff; padding: 0.6rem 1.5rem; font-size: 1rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px; vertical-align: middle;"><path d="M1 4v6h6"></path><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                        Reset to Default Banner
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="panel">
            <div class="panel-body">
                <p class="text-muted" style="margin: 0; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    You are currently using the default banner image.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection