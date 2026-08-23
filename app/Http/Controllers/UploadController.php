<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class UploadController extends Controller
{
    /**
     * Serve an uploaded file from public/uploads.
     *
     * Acts as a fallback when the web server cannot resolve the static file
     * (e.g. document root differs from Laravel's public path on shared hosting).
     */
    public function show(string $path)
    {
        $base = realpath(public_path('uploads'));
        $full = realpath(public_path('uploads/' . $path));

        if ($base === false || $full === false || !str_starts_with($full, $base . DIRECTORY_SEPARATOR) || !is_file($full)) {
            abort(404);
        }

        $response = response()->file($full);
        $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');

        return $response;
    }
}
