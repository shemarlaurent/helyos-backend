<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductImageController extends Controller
{
    // add product image
    public static function addImages(Product $product, Collection $images)
    {
        $images->each(function ($image) use($product) {
            $product->images()->create([
                'url' => $image
            ]);
        });
    }
}
