<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Notifications\User\PurchasedPricedProduct;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PricedProductController extends Controller
{
    /** Process the request to buy a priced product
     * @param Collection $request
     * @return bool
     */
    public function buyPricedProduct($request)
    {

        $product = Product::find($request['product']);
        // create a priced product record
        auth('api')->user()->pricedProducts()->create([
            'product_id' => $product->id,
            'amount' => $request['pricedProduct']
        ]);

        // notify the user of the PYP purchase
//        auth('api')->user()->notify(new PurchasedPricedProduct($product, $request['pricedProduct']));

        return true;
    }
}
