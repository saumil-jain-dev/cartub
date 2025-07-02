<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

function uploadImage($file, $folder)
{

    $destinationPath = public_path('images/' . $folder);

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }

    $image = 'file_' . time() . '.' . $file->getClientOriginalExtension();
    $file->move($destinationPath, $image);

    return 'images/' . $folder . '/' . $image;
}

if (!function_exists('uploadMultipleImages')) {
    function uploadMultipleImages($files,  $folder): array
    {
        $destinationPath = public_path('images/' . $folder);

        // Ensure the directory exists, create if necessary
        if (!File::isDirectory($destinationPath)) {
            // Create the directory recursively
            mkdir($destinationPath, 0777, true);
        }

        $uploadedImages = []; // To store the paths of uploaded images

        if (count($files) > 0) {
            foreach ($files as $file) {
                $uniqueId = uniqid();
                $imageName = 'file_' . $uniqueId . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $imageName);
                $uploadedImages[] = 'images/' . $folder . '/' . $imageName; // Save relative path
            }
        }


        return $uploadedImages; // Return the list of uploaded image paths
    }
}


function    getImage($file)
{
    if (file_exists(public_path($file)) && $file) {
        return asset('/public/' . $file);
    } else {
        return asset('/public/images/no-image.jpg');
    }
}

function deleteImage($file)
{
    if (file_exists(public_path($file))) {
        unlink(public_path($file));
        return true;
    } else {
        return false;
    }
}

function getLoadImage($file)
{
    if (file_exists(public_path($file)) && $file) {
        return asset('/public/' . $file);
    } else {
        return null; // Return null instead of default image if file doesn't exist
    }
}
