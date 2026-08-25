<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageOptimizerTrait
{
    /**
     * Optimize and store an uploaded image.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @return string
     */
    public function optimizeAndStoreImage($file, $path, $maxWidth = 1920, $maxHeight = 1080, $quality = 80)
    {
        $realPath = $file->getRealPath();
        $mime = $file->getMimeType();

        // Check if GD supports this mime
        $image = null;
        switch ($mime) {
            case 'image/jpeg':
                $image = @imagecreatefromjpeg($realPath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($realPath);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($realPath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($realPath);
                break;
        }

        // If not a supported image or GD failed, just store the original
        if (!$image) {
            return $file->store($path, 'public');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Resize if it exceeds max bounds
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG and WEBP
            if ($mime == 'image/png' || $mime == 'image/webp') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        // Save optimized image to a temp file
        $extension = $file->getClientOriginalExtension();
        if (!$extension) $extension = 'jpg';
        $tempPath = sys_get_temp_dir() . '/' . uniqid('img_') . '.' . $extension;
        
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($image, $tempPath, $quality);
                break;
            case 'image/png':
                // PNG quality is 0-9 (compression level, so inverse of 0-100)
                $pngQuality = max(0, min(9, round((100 - $quality) / 10)));
                imagepng($image, $tempPath, $pngQuality);
                break;
            case 'image/gif':
                imagegif($image, $tempPath);
                break;
            case 'image/webp':
                imagewebp($image, $tempPath, $quality);
                break;
        }
        
        imagedestroy($image);

        // Store using Laravel storage
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $storedPath = $path . '/' . $filename;
        Storage::disk('public')->put($storedPath, file_get_contents($tempPath));

        // Ensure temp file is cleaned up as requested
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }

        return $storedPath;
    }
}
