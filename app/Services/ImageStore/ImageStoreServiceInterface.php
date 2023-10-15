<?php

namespace App\Services\ImageStore;

use Illuminate\Http\UploadedFile;

interface ImageStoreServiceInterface
{
    public function store(UploadedFile $image): mixed;
    public function delete(string $imageName): bool;
}
