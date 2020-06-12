<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class SearchController extends Controller
{
    public function search($query)
    {
        return Product::search($query)->get()->load('variations');
    }

    public function searchWithFilters(Request $request)
    {
        dd($request->all());
        $products = Product::with('variations')->newQuery();

        $products->where('name', $request->input('query'))
        ->orWhere('name', 'like', '%' . $request->input('query'). '%');

        if ($request->has('brands')) {
            $brands = collect($request->input('barands'));

            $brands->each(function ($brand) use($products, $request) {
                $products->where('brand', $brand);
            });
        }

        if ($request->has('size')) {
            $sizes = collect($request->input('sizes'));
            $sizes->each(function ($size) use ($request, $products) {
                $products->whereHas('variations', function ($query) use($request, $size) {
                    $query->where('product_variations.name', json_encode(strtoupper($size)));
                });
            });
        }

        if ($request->has('price')) {
            $price = $request->input('price');

            $products->whereBetween('price', [$price['min'], $price['min']]);
        }

        return response()->json($products->get());
    }

    public function filterParams()
    {
        // get all the brands data
        $brands = DB::table('products')
            ->distinct('brand')
            ->select('brand')->get();

        // get distinct sizes on the product variations table
        $sizes = DB::table('product_variations')
            ->distinct('name')->select('name')
            ->get();

        return response()->json([
            'brands' => $brands,
            'sizes' => $sizes
        ]);
    }


    /**
     * populate search page with some products
     */
    public function searchList()
    {
        $products = Product::with(['images', 'variations'])
            ->inRandomOrder()
            ->take(30)
            ->get();

        return response()->json($products);
    }
}
