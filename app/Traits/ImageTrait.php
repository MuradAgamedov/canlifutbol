<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function getImage($imageName)
    {
        return Storage::url($imageName);
    }
}
