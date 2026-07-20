<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// ✅ Sirf receipt image ka kaam yahan hoga (upload/delete).
// Kal agar storage disk change karna ho (jaise local se S3),
// sirf isi class ko edit karo — ExpenseService ko haath nahi lagana parega (OCP)
class ReceiptImageService
{
    public function upload(UploadedFile $file): string
    {
        return $file->store('receipts', 'public');
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    public function replace(?string $oldPath, UploadedFile $newFile): string
    {
        $this->delete($oldPath);
        return $this->upload($newFile);
    }
}
