<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductVariationController extends Controller
{
    // add different size variations for a product
    public static function addVariations(Product $product, Collection $variations)
    {
        $variations->each(function ($variation) use ($product) {
                if(!empty($variation['size'])&& !empty($variation['price']) && !empty($variation['quantity'])) {
                    $product->variations()->create([
                        'name' => strtoupper($variation['size']),
                        'price' => $variation['price'],
                        'quantity' => $variation['quantity']
                    ]);
                }
        });
    }
}
