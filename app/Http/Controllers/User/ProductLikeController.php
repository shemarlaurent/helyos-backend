<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Product;
use Illuminate\Http\Request;

class ProductLikeController extends Controller
{
    public function toggleLike(Product $product)
    {
        $request = request('like');

        $product->toggleLike(auth('api')->user(), $request->input('value'));

        return new ProductResource($product);
    }
}
