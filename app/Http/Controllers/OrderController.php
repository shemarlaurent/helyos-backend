<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\PaymentController;
use App\NebulaPayment;
use App\Order;
use App\Product;
use App\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Model $product
     * @param $size
     * @param $amount
     * @param bool $status
     * @return Order
     */
    public function store(Product $product, $size, $amount, $type, $status = true)
    {
        $productVariation = $product->variations->where('name', strtoupper($size))->first();
        return auth('api')->user()->orders()->create([
            'store_id' => $product->store_id,
            'code' => Str::random(10),
            'product_variation_id' => $productVariation->id,
            'amount' => $amount,
            'type' => $type,
            'status' => $status,
        ]);
    }


    /**
     * Reorder a product
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function buyAgain(Order $order)
    {
        if ($order->nebula) {
            $order = (new NebulaPaymentController)->reOrder($order);

            if ($order) {
                return response()->json(true);
            }

            else return response()->json('payment declined', 401);
        }

        else {
            return $this->reOrder($order);
        }
    }

    private function reOrder(Order $order)
    {
        $price = $order->product_variation->price;
        $payment = (new PaymentController)->chargeUser($price, auth('api')->user());

        if ($payment) {
            (new OrderController)->store($order->product_variation->product, $order->product_variation->name, $order->product_variation->price, 'normal');

            $product = $order->product_variation->product;

            $product->sell($order->product_variation->name);

            $product->store->seller->credit($order->product_variation->price);

            return response()->json('success');
        }

        else return response()->json('payment declined', 401);
    }
}
