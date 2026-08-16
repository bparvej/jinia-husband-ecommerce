@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h2 class="page-title">General Settings</h2>
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

<div class="form-grid">
    <div class="form-main" style="max-width: 800px;">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="product-form">
            @csrf
            
            <div class="panel">
                <div class="panel-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        Media Upload Preferences
                    </h3>
                </div>
                <div class="panel-body">
                    
                    <!-- Maximum Image Size Slider -->
                    <div class="form-group" x-data="{ sizeMb: {{ round(old('max_image_size', $maxImageSize) / 1024) }} }">
                        <label for="max_image_size_slider" style="font-weight: 600; font-size: 1.05rem;">Maximum Image File Size</label>
                        <p class="text-muted" style="margin-top: 0.25rem; margin-bottom: 1rem; font-size: 0.9rem;">
                            Limit the file size to prevent users from uploading massive photos that slow down your website.
                        </p>
                        
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <input 
                                type="range" 
                                id="max_image_size_slider" 
                                min="1" 
                                max="50" 
                                step="1"
                                x-model="sizeMb"
                                style="flex-grow: 1; height: 6px; border-radius: 4px; accent-color: #2563eb;"
                            >
                            <div style="font-size: 1.25rem; font-weight: 700; color: #2563eb; min-width: 80px; text-align: right;">
                                <span x-text="sizeMb"></span> MB
                            </div>
                        </div>
                        
                        <!-- Hidden input to send KB to backend -->
                        <input type="hidden" name="max_image_size" :value="sizeMb * 1024">
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8rem; color: #6b7280;">
                            <span>1 MB (Smallest)</span>
                            <span>25 MB</span>
                            <span>50 MB (Largest)</span>
                        </div>
                    </div>

                    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">

                    <!-- Supported Image Formats Checkboxes -->
                    @php
                        $currentFormats = explode(',', strtolower(old('supported_image_formats', $supportedImageFormats)));
                    @endphp
                    <div class="form-group" x-data="{ 
                        formats: {{ json_encode($currentFormats) }},
                        toggleFormat(format) {
                            if (this.formats.includes(format)) {
                                this.formats = this.formats.filter(f => f !== format);
                            } else {
                                this.formats.push(format);
                            }
                        }
                    }">
                        <label style="font-weight: 600; font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">Allowed Image Types</label>
                        <p class="text-muted" style="margin-bottom: 1.25rem; font-size: 0.9rem;">
                            Select which types of images can be uploaded. We recommend keeping WebP enabled for faster page loads.
                        </p>

                        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                            
                            <!-- WebP Option -->
                            <label class="format-option" style="display: flex; align-items: flex-start; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: border-color 0.2s;" :style="formats.includes('webp') ? 'border-color: #2563eb; background-color: #eff6ff;' : ''">
                                <input type="checkbox" @change="toggleFormat('webp')" :checked="formats.includes('webp')" style="margin-top: 0.25rem; accent-color: #2563eb;">
                                <div style="margin-left: 0.75rem;">
                                    <div style="font-weight: 600; color: #111827;">WebP <span style="background-color: #dbeafe; color: #1d4ed8; font-size: 0.7rem; padding: 2px 6px; border-radius: 12px; margin-left: 4px;">Recommended</span></div>
                                    <div style="font-size: 0.85rem; color: #4b5563; margin-top: 0.2rem;">A modern format that provides superior, high-quality images at much smaller file sizes. Great for fast website loading.</div>
                                </div>
                            </label>

                            <!-- JPEG Option -->
                            <label class="format-option" style="display: flex; align-items: flex-start; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: border-color 0.2s;" :style="(formats.includes('jpeg') || formats.includes('jpg')) ? 'border-color: #2563eb; background-color: #eff6ff;' : ''">
                                <input type="checkbox" @change="toggleFormat('jpeg'); toggleFormat('jpg')" :checked="formats.includes('jpeg') || formats.includes('jpg')" style="margin-top: 0.25rem; accent-color: #2563eb;">
                                <div style="margin-left: 0.75rem;">
                                    <div style="font-weight: 600; color: #111827;">JPEG / JPG</div>
                                    <div style="font-size: 0.85rem; color: #4b5563; margin-top: 0.2rem;">The standard format for photographs and complex images. Widely supported across all devices.</div>
                                </div>
                            </label>

                            <!-- PNG Option -->
                            <label class="format-option" style="display: flex; align-items: flex-start; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: border-color 0.2s;" :style="formats.includes('png') ? 'border-color: #2563eb; background-color: #eff6ff;' : ''">
                                <input type="checkbox" @change="toggleFormat('png')" :checked="formats.includes('png')" style="margin-top: 0.25rem; accent-color: #2563eb;">
                                <div style="margin-left: 0.75rem;">
                                    <div style="font-weight: 600; color: #111827;">PNG</div>
                                    <div style="font-size: 0.85rem; color: #4b5563; margin-top: 0.2rem;">Best for graphics, logos, or images that require a transparent background.</div>
                                </div>
                            </label>

                        </div>

                        <!-- Hidden input to send comma-separated formats to backend -->
                        <input type="hidden" name="supported_image_formats" :value="formats.join(',')">
                    </div>

                    <div style="margin-top: 2.5rem; display: flex; gap: 1rem; align-items: center;">
                        <button type="submit" class="btn-admin btn-primary-admin" style="padding: 0.6rem 1.5rem; font-size: 1rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Save Changes
                        </button>
                        
                        <a href="{{ route('admin.dashboard') }}" class="btn-admin" style="background-color: transparent; border: 1px solid #d1d5db; color: #4b5563; padding: 0.6rem 1.5rem; font-size: 1rem; text-decoration: none; border-radius: 6px;">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Add some hover effects for the custom checkboxes */
    .format-option:hover {
        background-color: #f9fafb;
    }
</style>
@endsection
