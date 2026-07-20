<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// ✅ Sirf product image ka kaam (upload/delete).
// Kal agar storage disk change karni ho, sirf isi class ko edit karo. (OCP)
class ProductImageService
{
    public function upload(UploadedFile $file): string
    {
        $imageName = time() . '.' . $file->extension();
        $file->storeAs('products', $imageName, 'public');

        return $imageName;
    }

    public function delete(?string $imageName): void
    {
        if ($imageName && Storage::disk('public')->exists('products/' . $imageName)) {
            Storage::disk('public')->delete('products/' . $imageName);
        }
    }

    public function replace(?string $oldImageName, UploadedFile $newFile): string
    {
        $this->delete($oldImageName);
        return $this->upload($newFile);
    }
}
