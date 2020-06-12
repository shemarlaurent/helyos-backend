<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** return products
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Product::with(['images', 'variations'])
            ->latest('created_at')
            ->paginate(32));
    }


    /** show a specific product
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product)
    {
        $related = Product::with('images')
            ->where('name', 'like', '%'. $product->name)
            ->limit(30)
            ->get();
        return response()->json([
            'product' => $product->load(['variations', 'likes']),
            'related' => $related
        ]);
    }


    /** returns list of featured products
     * @return JsonResponse
     */
    public function featured()
    {
        $products = Product::with(['images', 'variations'])
            ->where('featured', 1)
            ->inRandomOrder()
            ->limit(50)
            ->get();

        return response()->json($products);
    }

}
