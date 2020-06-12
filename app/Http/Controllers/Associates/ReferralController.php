<?php

namespace App\Http\Controllers\Associates;

use App\Http\Controllers\Controller;
use App\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    //get details for the associates referrals
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
