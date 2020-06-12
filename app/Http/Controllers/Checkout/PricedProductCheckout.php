<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PricedProductController;
use App\PricedProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PricedProductCheckout extends Controller
{
    //
    public function checkout($request, $card)
    {
        // attempt to charge the customer for the full cart amount or return error

        if (isset($card)) {
            $payment = (new PaymentController)->charge($card, $request['pricedProduct']);
        }

        else {
            $payment = (new PaymentController)->chargeUser($request['pricedProduct'], auth('api')->user());
        }

        // store the order for the payment received
        if ($payment) {
            $product = Product::where('id', $request['product'])->first();

            $order = (new OrderController)->store($product, $request['size'], $request['pricedProduct'], 'priced' );

            $product->sell($request['size']);

            // save the users PYP transaction
            (new PricedProductController)->buyPricedProduct($request);

            $product->store->seller->credit($request['price']);

            return response()->json($order);
        }

        else {
            return response()->json([
                'payment' => 'Payment method has been declined'
            ], 403);
        }
    }
}
