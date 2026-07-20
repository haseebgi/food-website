<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

// ✅ Sirf variants (sizes) create karne ka kaam yahan hoga.
// Kal agar variant banane ka tareeqa change ho (jaise size ke sath
// color bhi add karna ho), sirf isi class ko edit karo. (OCP)
class ProductVariantService
{
    public function createVariants(Product $mainProduct, array $variants): void
    {
        foreach ($variants as $variant) {
            if (!empty($variant['size']) && !empty($variant['price'])) {
                Product::create([
                    'category_id'   => $mainProduct->category_id,
                    'name'          => $mainProduct->name,
                    'slug'          => Str::slug($mainProduct->name . '-' . $variant['size']),
                    'image'         => $mainProduct->image,
                    'description'   => $mainProduct->description,
                    'cost_price'    => $mainProduct->cost_price,
                    'selling_price' => $variant['price'],
                    'quantity'      => $mainProduct->quantity,
                    'min_stock'     => $mainProduct->min_stock,
                    'status'        => $mainProduct->status,
                    'parent_id'     => $mainProduct->id,
                    'size'          => $variant['size'],
                ]);
            }
        }
    }
}
