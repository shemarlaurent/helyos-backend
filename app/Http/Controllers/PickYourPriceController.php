<?php

namespace App\Http\Controllers;

use App\PricedProduct;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PickYourPriceController extends Controller
{

    /** get daily priced product availability and usage count for the month
     * @return JsonResponse
     */
    public function sales()
    {
        $dailySales = PricedProduct::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();

        $userUsage = PricedProduct::where('user_id', auth('api')->id())
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();

        return response()->json([
            'dailySales' => $dailySales,
            'userUsage' => $userUsage,
        ]);
    }
}
