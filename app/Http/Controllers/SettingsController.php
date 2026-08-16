<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Show the settings form.
     */
    public function index()
    {
        $maxImageSize = Setting::get('max_image_size', '2048');
        $supportedImageFormats = Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

        return view('admin.settings.index', [
            'maxImageSize' => $maxImageSize,
            'supportedImageFormats' => $supportedImageFormats,
            'title' => 'Settings — HomeI Admin',
        ]);
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'max_image_size' => 'required|integer|min:100|max:51200', // 100KB to 50MB
            'supported_image_formats' => 'required|string|regex:/^[a-zA-Z0-9,]+$/',
        ], [
            'max_image_size.required' => 'Maximum image size is required.',
            'supported_image_formats.required' => 'Supported image formats are required.',
            'supported_image_formats.regex' => 'Supported image formats must be comma-separated values (e.g. jpeg,jpg,png).',
        ]);

        try {
            Setting::set('max_image_size', $request->input('max_image_size'));
            
            // Clean up formats (remove spaces)
            $formats = str_replace(' ', '', strtolower($request->input('supported_image_formats')));
            Setting::set('supported_image_formats', $formats);

            Log::info("Settings updated by user ID: " . auth()->id());

            return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to update settings: " . $e->getMessage());
            return redirect()->route('admin.settings.index')->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}
