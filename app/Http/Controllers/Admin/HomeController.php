<?php

namespace App\Http\Controllers\Admin;

use App\AbyssUser;
use App\Affiliate;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\Referral;
use App\Seller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('admin.auth:admin');
    }

    /**
     * Show the Admin dashboard.
     *
     * @return JsonResponse
     */
    public function index() {
        $users = User::count();

        $sellers = Seller::count();

        $affiliates = Affiliate::count();

        $abyssUsers = AbyssUser::count();

        $orders = Order::with(['orderable', 'product_variation.product'])->latest('created_at')->take(5)->get();

        $weeklyRevenue = DB::table('orders')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');

        $revenue = DB::table('orders')->sum('amount');

        $currentWeeksOrders = DB::table('orders')
            ->select(['amount', 'created_at'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();

        $previousWeeksOrders = DB::table('orders')
            ->select(['amount', 'created_at'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();

        return response()->json([
            'users' => $users,
            'sellers' => $sellers,
            'affiliates' => $affiliates,
            'abyssUsers' => $abyssUsers,
            'orders' => $orders,
            'weeklyRevenue' => $weeklyRevenue,
            'revenue' => $revenue,
            'currentWeeksOrders' => $currentWeeksOrders,
            'previousWeeksOrders' => $previousWeeksOrders
        ]);
    }

    public function chart()
    {
        $currentWeeksOrders = DB::table('orders')
            ->select(['amount', 'created_at'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();

        $previousWeeksOrders = DB::table('orders')
            ->select(['amount', 'created_at'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();

        return response()->json([
            'currentWeeksOrders' => $currentWeeksOrders,
            'previousWeeksOrders' => $previousWeeksOrders
        ]);
    }

    public function sellers()
    {
        $sellers = Seller::with('store')->get();

        return response()->json($sellers);

    }

    public function affiliates()
    {
        $affiliates = Affiliate::addSelect([
            'referrals' => Referral::selectRaw('count(id)')
            ->where('referable_id', 'affiliates.id')
            ->limit(1)
        ])->get();

        return response()->json($affiliates);
    }

}
