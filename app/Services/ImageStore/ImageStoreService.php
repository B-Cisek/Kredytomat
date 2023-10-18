<?php

declare(strict_types=1);

namespace App\Services\ImageStore;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageStoreService implements ImageStoreServiceInterface
{
    private const FOLDER_NAME = 'logos';
    private string|false $link;

    public function store(UploadedFile $image): static
    {
        $this->link = $image->store(self::FOLDER_NAME, 'public');

        return $this;
    }

    public function delete(string $imageName): bool
    {
        return Storage::disk('public')->delete($imageName);
    }

    public function getLink(): false|string
    {
        return $this->link;
    }
}
