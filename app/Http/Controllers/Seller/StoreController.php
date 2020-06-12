<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Order;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {

        $customers = DB::table('orders')
            ->distinct('orderable_id')
            ->where('store_id', auth()->user()->store->id)
            ->count();

        $products = auth()->user()->store->products()->count();

        $ordersCount = auth()->user()->store->orders()->count();

        $orders = auth()->user()->store->orders()->with(['orderable', 'product_variation.product'])->latest('created_at')->limit(4)->get();

        $monthlyEarning = DB::table('orders')
            ->where('store_id', auth()->user()->store->id)
            ->whereBetween('created_at',
                [
                    Carbon::now()->startOfMonth()->format('Y-m-d H:i'),
                    Carbon::now()->endOfMonth()->format('Y-m-d H:i')
                ])
            ->sum('amount');


        return response()->json([
            'customers' => $customers,
            'products' => $products,
            'ordersCount' => $ordersCount,
            'orders' => $orders,
            'earning' => $monthlyEarning
        ]);
    }

    public function seller()
    {
        return response()->json(auth()->user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json([
            'store' => auth('seller')->user()->store()->create($request->all())
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function update(Request $request, Store $store)
    {
        return response()->json($store->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Store $store
     * @return bool
     */
    public function destroy(Store $store) : bool
    {
        foreach ($store->products as $product) {
            foreach ($product->images as $image) {
                $image->delete();
            }

            $product->delete();
        }

        return true;
    }
}
