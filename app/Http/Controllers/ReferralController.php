<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    //
    public function getReferrer(string $token)
    {
        if($referrer = Affiliate::where('code', $token)->first()) {
            return $referrer;
        }

        return User::where('token', $token)->first();
    }
}
