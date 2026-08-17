<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

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

    public const DEFAULT_BANNER = '/assets/images/hero-living-room.png';

    /**
     * Show the banner settings form.
     */
    public function banner()
    {
        $bannerImage = Setting::get('banner_image', self::DEFAULT_BANNER);
        $maxImageSize = Setting::get('max_image_size', '2048');
        $supportedImageFormats = Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

        return view('admin.settings.banner', [
            'bannerImage' => $bannerImage,
            'maxImageSize' => $maxImageSize,
            'supportedImageFormats' => $supportedImageFormats,
            'title' => 'Banner Settings — HomeI Admin',
        ]);
    }

    /**
     * Update the banner image.
     */
    public function updateBanner(Request $request)
    {
        $maxImageSize = Setting::get('max_image_size', '2048');
        $supportedImageFormats = Setting::get('supported_image_formats', 'jpeg,jpg,png,webp');

        $request->validate([
            'banner_image' => 'required|image|mimes:' . $supportedImageFormats . '|max:' . $maxImageSize,
        ], [
            'banner_image.required' => 'Please choose a banner image to upload.',
            'banner_image.image' => 'The selected file must be an image.',
            'banner_image.max' => 'The image must not be larger than ' . round($maxImageSize / 1024, 1) . 'MB.',
            'banner_image.mimes' => 'The image must be a file of type: ' . $supportedImageFormats . '.',
        ]);

        try {
            $file = $request->file('banner_image');
            $filename = 'banner_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);

            $this->deleteUploadedBanner(Setting::get('banner_image', self::DEFAULT_BANNER));

            Setting::set('banner_image', '/uploads/banners/' . $filename);

            Log::info("Banner image updated by user ID: " . auth()->id());

            return redirect()->route('admin.settings.banner')->with('success', 'Banner image updated successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to update banner: " . $e->getMessage());
            return redirect()->route('admin.settings.banner')->with('error', 'Failed to update banner: ' . $e->getMessage());
        }
    }

    /**
     * Reset the banner to the default image.
     */
    public function resetBanner(Request $request)
    {
        try {
            $this->deleteUploadedBanner(Setting::get('banner_image', self::DEFAULT_BANNER));
            Setting::set('banner_image', self::DEFAULT_BANNER);

            Log::info("Banner reset to default by user ID: " . auth()->id());

            return redirect()->route('admin.settings.banner')->with('success', 'Banner has been reset to the default image.');
        } catch (\Exception $e) {
            Log::error("Failed to reset banner: " . $e->getMessage());
            return redirect()->route('admin.settings.banner')->with('error', 'Failed to reset banner: ' . $e->getMessage());
        }
    }

    /**
     * Delete a previously uploaded banner file (keeps the default safe).
     */
    private function deleteUploadedBanner(?string $path): void
    {
        if (!$path || !str_starts_with($path, '/uploads/banners/')) {
            return;
        }

        $fullPath = public_path($path);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    // --- Email Template Management ---

    public function emailTemplates()
    {
        $templates = EmailTemplate::orderBy('is_default', 'desc')->orderBy('label')->get();

        return view('admin.settings.email-templates', [
            'templates' => $templates,
            'title' => 'Email Templates — HomeI Admin',
        ]);
    }

    public function updateEmailTemplate(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
        ]);

        try {
            $template->update([
                'subject' => $request->input('subject'),
                'body_html' => $request->input('body_html'),
            ]);

            Log::info("Email template updated: " . $template->name . " by user ID: " . auth()->id());

            return redirect()->route('admin.settings.email-templates')->with('success', 'Template "' . $template->label . '" updated successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to update email template: " . $e->getMessage());
            return redirect()->route('admin.settings.email-templates')->with('error', 'Failed to update template: ' . $e->getMessage());
        }
    }

    public function setActiveEmailTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);

        try {
            EmailTemplate::where('is_active', true)->update(['is_active' => false]);
            $template->update(['is_active' => true]);

            Log::info("Active email template set to: " . $template->name . " by user ID: " . auth()->id());

            return redirect()->route('admin.settings.email-templates')->with('success', '"' . $template->label . '" is now the active customer email template.');
        } catch (\Exception $e) {
            Log::error("Failed to set active email template: " . $e->getMessage());
            return redirect()->route('admin.settings.email-templates')->with('error', 'Failed to set active template: ' . $e->getMessage());
        }
    }

    public function previewEmailTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);

        // Build a sample order for preview
        $sampleOrder = (object) [
            'order_number' => 'HI-20260817-DEMO01',
            'subtotal' => 4500.00,
            'shipping_cost' => 200.00,
            'total' => 4700.00,
            'shipping_name' => 'Jinia Akter',
            'shipping_phone' => '01712345678',
            'shipping_address' => '123 Tejgaon, Road 5',
            'shipping_city' => 'Dhaka',
            'items' => collect([
                (object) ['product_name' => 'Wooden Coffee Table', 'quantity' => 1, 'unit_price' => 2500.00, 'total_price' => 2500.00],
                (object) ['product_name' => 'Bamboo Planter Set', 'quantity' => 2, 'unit_price' => 1000.00, 'total_price' => 2000.00],
            ]),
        ];

        $sampleUser = (object) [
            'name' => 'Jinia Akter',
            'email' => 'jinia@example.com',
        ];

        $renderedSubject = Blade::render($template->subject, ['order' => $sampleOrder, 'user' => $sampleUser]);
        $renderedBody = Blade::render($template->body_html, ['order' => $sampleOrder, 'user' => $sampleUser]);

        return response()->json([
            'subject' => $renderedSubject,
            'body_html' => $renderedBody,
        ]);
    }
}
