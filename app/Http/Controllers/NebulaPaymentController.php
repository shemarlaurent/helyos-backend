<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\PaymentController;
use App\NebulaPayment;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Margules\bplib\BluePay;

class NebulaPaymentController extends Controller
{
    public function createPlan(Order $order,  $nebula)
    {
        return NebulaPayment::create(
        [
            'user_id' => auth('api')->id(),
            'order_id' => $order->id,
            'total' => $order->amount,
            'installments' => $nebula['installments'],
            'paid' => $nebula['installments'],
            'starts' => Carbon::now(),
            'ends' => Carbon::now()->addDays($nebula['days'])
        ]
        );
    }

    public function checkout($request, $card)
    {
        if (isset($card)) {
            $payment = (new PaymentController)->charge($card, $request['nebula']['installments']);
        }

        else {
            $payment = (new PaymentController)->chargeUser($request['nebula']['installments'], auth('api')->user());
        }

        if ($payment) {
            $product = Product::where('id', $request['product'])->first();

            $order = (new OrderController)->store($product, $request['size'], $request['price'], 'nebula', false);

            $this->createPlan($order, $request['nebula']);
//
//            $product->sell($request['size']);
//
//            $product->store->seller->credit($request['price']);

            return response()->json($order);
        }
    }

    public function cancelNebulaOrder(Order $order)
    {
        $nebulaPlan = NebulaPayment::with('')
            ->where('order_id', $order->id)
            ->first();

        if ($nebulaPlan->paid < $nebulaPlan->total) {
            $reminder = $nebulaPlan->total - $nebulaPlan->paid;

            // attempt to charge the user  the reminder of the transaction
            $payment = $nebulaPlan->chargeReminder($reminder, auth('api')->user());

            if ($payment) {
                $nebulaPlan->updateDetails($reminder);
            }

            else $order->cancel();
        }
    }

    public function reOrder(Order $order)
    {
        $payment = (new PaymentController)->chargeUser($order->nebula->installments, auth('api')->user());

        if ($payment) {
            $plan = NebulaPayment::create(
                [
                    'user_id' => auth('api')->id(),
                    'order_id' => $order->id,
                    'total' => $order->amount,
                    'installments' => $order->nebula->installments,
                    'paid' => $order->nebula->installments,
                    'starts' => Carbon::now(),
                    'ends' => Carbon::now()->addDays($order->nebula->ends->diffInDays())
                ]
            );
            $order = (new OrderController)->store($order->product_variation->product, $order->product_variation->name, $order->product_variation->price, 'nebula', false);


            return true;
        }

        else return false;

    }
}
