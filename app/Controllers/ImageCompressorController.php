<?php

class ImageCompressorController
{
    public static function compress($source, $destination, $ext)
    {
        switch (strtolower($ext)) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $destination, 80);
                break;
            case 'png':
                $image = imagecreatefrompng($source);
                imagepng($image, $destination, 8);
                break;
            case 'webp':
                $image = imagecreatefromwebp($source);
                imagewebp($image, $destination, 80);
                break;
            default:
                move_uploaded_file($source, $destination);
                return;
        }

        imagedestroy($image);
    }
}
