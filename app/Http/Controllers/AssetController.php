<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

/**
 * Class AssetController
 *
 * This controller handles requests for serving static assets like CSS and JavaScript files.
 */
class AssetController extends Controller
{
    /**
     * Serve a static asset file.
     *
     * This method retrieves a file from the public directory and serves it
     * with the appropriate MIME type.
     *
     * @param string|null $path The relative path of the file in the public directory
     * @return \Illuminate\Http\Response
     */
    public function getFile(?string $path = null)
    {
        // If no path is provided, return a 404 Not Found error
        if (is_null($path)) {
            abort(404, 'File not found');
        }

        // Construct the full file path by prepending the public path
        $filePath = public_path("{$path}");

        // Check if the file exists in the specified path
        if (!File::exists($filePath)) {
            // If the file doesn't exist, return a 404 Not Found error
            abort(404, 'File not found');
        }

        // Determine the MIME type based on the file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'css':
                $mimeType = 'text/css';
                break;
            case 'js':
                $mimeType = 'text/javascript';
                break;
            default:
                // For other file types, use Laravel's File facade to determine the MIME type
                $mimeType = File::mimeType($filePath);
                break;
        }

        // Return the file as a response with the correct content type
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);
    }
}