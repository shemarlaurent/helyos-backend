<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    /** Create membership for the users desired plan
     * @param User $user
     * @param $plan
     */
    public function subscribeUser(User $user, $plan) : void
    {
        $user->subscription()->create([
            'plan_id' => $plan,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonths(1)
        ]);
    }
}
