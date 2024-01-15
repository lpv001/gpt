<?php

namespace App\Helper;

class FileUploadHelper
{

    // Upload image 
    static function uploadImage($image, $imagePath)
    {
        $image_name = '';

        $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
        $image->move($imagePath, $filename);
        $image_name = $filename;

        return $image_name;
    }

    static function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
            return true;
        }

        return false;
    }
}
