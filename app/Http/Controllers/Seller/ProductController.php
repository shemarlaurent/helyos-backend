<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;

class ProductController extends Controller
{


    public function index()
    {
        return response()->json(auth()->user()->store->products()->with(['images', 'variations'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *

     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $product = Product::create([
            'store_id' => auth()->user()->store->id,
            'brand' => $request->input('brand'),
            'name' => $request->input('name'),
            'price' => $this->getAverage($request->input('variants')),
            'description' => $request->input('description'),
            'sku' => Str::random(8),
            'silohette' => $request->input('silohette'),
            'slug' => Str::slug($request->input('name') . rand(1, 9)),
            'featured' => $request->input('featured') || false,
        ]);

        ProductVariationController::addVariations($product, collect($request->input('variants')));

        // insert images array from cloudinary to the images table for the product
        ProductImageController::addImages($product, collect($request->input('images')));


        return response()->json($product->refresh());
    }

    public function fromCsv(Request $request)
    {
        foreach ($request['products'] as $key => $product) {
            $newProduct = Product::create([
                'store_id' => auth()->user()->store->id,
                'brand' => $product['brand'],
                'name' => $product['name'],
                'price' => $this->getAverage($product['variants']),
                'description' => $product['description'],
                'sku' => Str::random(8),
                'slug' => Str::slug($product['name'] . rand(1, 9)),
                'featured' => $product['featured'] || false,
            ]);

            ProductVariationController::addVariations($newProduct, collect($product['variants']));

            ProductImageController::addImages($newProduct, collect($request['images'][$key]));

        }

        return response()->json('done');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product) : JsonResponse
    {
        return response()->json($product->load(['images', 'variations']));
    }


    /**
     * @param Product $product
     * @return Builder[]|Collection
     */
    public function related(Product $product)
    {
        return Product::with('images')
            ->where('name', 'LIKE', "%{$product->name}%")
            ->limit(60)
            ->get();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        //
    }


    /** calculate the average price of the product
     * @param array|null $input
     * @return float|int
     */
    private function getAverage(array $input)
    {
        $variations = collect($input);

        $prices = $variations->map(function($variation) { return $variation['price'];});

        return $prices->sum() / $prices->count();
    }
}
