<?php

namespace App\Concerns;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

trait HandleImageUpload
{
    /**
     * Store and resize an avatar image.
     *
     * @param UploadedFile $file
     * @param int $width
     * @param int $height
     * @param string $directory
     * @return string
     */
    public function storeAndResizeImage(UploadedFile $file, int $width = 200, int $height = 200, string $directory = 'avatars'): string
    {
        Storage::disk('public')->makeDirectory($directory);

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $fileName;

        $image = Image::read($file)
            ->cover($width, $height)
            ->encode();

        Storage::disk('public')->put($path, $image);

        return $path;
    }
}
