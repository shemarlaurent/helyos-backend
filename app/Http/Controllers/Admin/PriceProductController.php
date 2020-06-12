<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PricedProduct;
use App\PricedProductItem;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class PriceProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::select(['id', 'name'])->get();
        return response()->json($products);
    }


    public function getDetails()
    {

        $pricedProducts = PricedProduct::with('product')
            ->latest('created_at')
            ->get();


        // et count of all priced product items
        $total = $pricedProducts->count();

        // get total items added for current week
        $currentWeek = $pricedProducts->whereBetween('created_at',
            [Carbon::now()
                ->startOfWeek()
                ->format('Y-m-d H:i'),
                Carbon::now()
                    ->endOfWeek()
                    ->format('Y-m-d H:i')])->all();


        // get the number of users that have used to the priced product feature
        $totalUsers = DB::table('priced_products')
            ->distinct('user_id')
            ->count();



        // total sales made from priced products
        $totalSales= DB::table('priced_products')
            ->select('amount')
            ->sum('amount');


        return response()->json([
            'total' => $total,
            'currentWeek' => $currentWeek,
            'totalUsers' => $totalUsers,
            'totalSales' => $totalSales
        ]);
    }
}
