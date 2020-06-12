<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function terminateSubscription()
    {
        $user = auth('api')->user();

        $user->subscription->status = false;
        $user->subscription->save();

        return response()->json([
            'status' => 'terminated',
            'subscription' => $user->subscription
        ]);
    }

    public function reviveSubscription()
    {
        $user = auth('api')->user();

        if ($user->subscription) {
            $user->subscription->status = true;
            $user->subscription->save();
        }

        return response()->json([
            'status' => 'revived',
            'subscription' => $user->subscription
        ]);
    }
}
