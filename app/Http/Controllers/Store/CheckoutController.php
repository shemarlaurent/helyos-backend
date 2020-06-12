<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Checkout\NebulaPayCheckout;
use App\Http\Controllers\Checkout\PricedProductCheckout;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NebulaPaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\User\PaymentController;
use App\PricedProduct;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CheckoutController extends Controller
{
    /** handle the checkout process of an order;
     * @param Request $request
     */
    public function checkout(Request $request)
    {
        $cart = collect($request->input('cart'));

        $card = $request->input('card');

        $cart->each(function($cartItem) use ($request, $card) {
            if (isset($cartItem['pricedProduct'])) {

                // process priced product checkout
                (new PricedProductCheckout)->checkout($cartItem, $card);
            }
            elseif(isset($cartItem['nebula'])) {
                (new NebulaPaymentController)->checkout($cartItem, $card);
            }

            else {
                $this->processCheckout($cartItem, $card);
            }
        });

        if (!auth('api')->user()->card && isset($card)) {
            (new CardController)->addCard(auth('api')->user(), $card);
        }

    }


    private function processCheckout($cartItem, $card)
    {

        $product = Product::where('id', $cartItem['product'])->first();

        if (isset($card)) {
            $payment = (new PaymentController)->charge($card, $cartItem['price']);
        }

        else {
            $payment = (new PaymentController)->chargeUser($cartItem['price'], auth('api')->user());
        }


        if ($payment) {

            $order = (new OrderController)->store($product, $cartItem['size'], $cartItem['price'], 'normal');

            $product->sell($cartItem['size']);

            $product->store->seller->credit($cartItem['price']);

            // save the users card for future orders


            return true;
        }

        else {
            return response()->json([
                'payment' => 'Payment method has been declined'
            ], 403);
        }


    }

}
