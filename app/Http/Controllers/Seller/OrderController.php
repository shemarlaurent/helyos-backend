<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        // get all store orders
        $orders = auth()->user()->store->orders()->with(['orderable', 'product_variation.product'])->latest('created_at')->get();

        return response()->json($orders);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return JsonResponse
     */
    public function show(Order $order)
    {
        return response()->json($order);
    }


}
