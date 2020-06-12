<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Referral;

class HomeController extends Controller
{

    public function home()
    {
        $referrals = Referral::with('user')
            ->where('referable_id', auth()->id())
            ->get();


        $earning = auth()->user()->earnings;

        return response()->json([
            'referrals' => $referrals,
            'earning' => $earning
        ]);
    }

}
