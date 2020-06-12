<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Referral;
use App\User;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * @param User $user
     * @param $plan
     */
    public function createPlan(User $user, $plan)
    {
            // subscribe the user to a plan

            $plan = \App\SubscriptionPlan::where('id', $plan)->first();

            if ($plan) {

                // TODO: Handle BluePay Integration
                if((new PaymentController)->subscriptionCharge($user, $plan))
                {
                    (new SubscriptionController)->subscribeUser($user, $plan->id);
                }

            }
        }
}
