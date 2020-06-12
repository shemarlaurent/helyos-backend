<?php

namespace App\Http\Controllers\User;

use App\Forum;
use App\Http\Controllers\Controller;
use App\Referral;
use App\SubscriptionPlan as Plan;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Margules\bplib\BluePay;

class PaymentController extends Controller
{
    /* Subscribe the user for a recurring billing plan
     * @param User $user
     * @param Plan $plan
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function subscriptionCharge(User $user, Plan $plan)
    {

        $billing = new BluePay;

        $billing->setCustomerInformation([
            'firstName' => explode(' ', $user->name)[0],
            'lastName' => explode(' ', $user->name)[1],
            'email' => $user->email,
        ]);

        $billing->setCCInformation([
            'cardNumber' => $user->card->card_number,
            'cardExpire' => $user->card->card_expiration,
            'cvv2' => $user->card->card_cvv
        ]);


//        $billing->sale(intval($plan->amount));
        $billing->sale(intval(25));

        $billing->process();

        // credit the referrer 10% of the subscription amount
        if($billing->isSuccessfulResponse()) {
            (new SubscriptionController)->subscribeUser($user, $plan->id);


            $referral = Referral::where('user_id', $user->id)->first();

            if ($referral) {
                if ($referral->referable instanceof User) {
                    // associate earning
                    $amount = (2.5 / 100) * $plan->amount;
                }
                else {
                    // affiliate earning
                    $amount = (3 / 100) * $plan->amount;
                }
                $referral->referable->credit($amount);
            }

            return true;
        }

        else {
            return false;
        }
    }


    /** Charge to user for access to a forum
     * @param Request $request
     * @return bool
     */
    public function payForForum(Request $request)
    {
        $payment = new BluePay();

        $payment->setCustomerInformation([
            'firstName' => $request->input('first_name'),
            'lastName' => $request->input('last_name'),
            'email' => $request->input('email')
        ]);

        $payment->setCCInformation([
            'cardNumber' => $request->input('card'),
            'cardExpire' => implode('', explode('/', $request->input('card_expire'))),
            'cvv2' => $request->input('cvv2')
        ]);


        $payment->sale('1.00');

        $payment->process();

        if ($payment->isSuccessfulResponse()) {
            return true;
        }

        return false;
    }


    /** perform a single charge transaction on a users card for a purchase
     * @param $card
     * @param $amount
     * @return bool
     */
    public function charge($card, $amount) : bool
    {
        $payment = new BluePay();

        $user = auth('api')->user();

        $payment->setCustomerInformation([
            'firstName' => explode(' ', $user->name)[0],
            'lastName' => explode(' ', $user->name)[1],
            'email' => $user->email
        ]);

        $payment->setCCInformation([
            'cardNumber' =>   $card['cardNumber'],
            'cardExpire' => implode('', explode('/', $card['cardExpiration'])),
            'cvv2' => $card['cvv2']
        ]);


        $payment->sale(floatval($amount));

        $payment->process();

        if ($payment->isSuccessfulResponse()) {
            return true;
        }

        return false;
    }

    public function chargeUser($amount, $user)
    {
        $payment = new BluePay();

        $payment->setCustomerInformation([
            'firstName' => explode(' ', $user->name)[0],
            'lastName' => explode(' ', $user->name)[1],
            'email' => $user->email
        ]);

        $payment->setCCInformation([
            'cardNumber' =>   $user->card->card_number,
            'cardExpire' => $user->card->card_expiration,
            'cvv2' => $user->card->card_cvv
        ]);


        $payment->sale(floatval($amount));

        $payment->process();

        if ($payment->isSuccessfulResponse()) {
            return true;
        }

        return false;
    }

    public function chargeAbyss($user)
    {
        $payment = new BluePay();

        $user = auth('abyss_user')->user();

        $payment->setCustomerInformation([
            'firstName' => explode(' ', $user->name)[0],
            'lastName' => explode(' ', $user->name)[1],
            'email' => $user->email
        ]);

        $payment->setCCInformation([
            'cardNumber' =>   $user->card,
            'cardExpire' => implode('', explode('/', $user->card_expire)),
            'cvv2' => $user->cvv2
        ]);


        $payment->sale('1.0');

        $payment->process();

        if ($payment->isSuccessfulResponse()) {
            return true;
        }

        return false;
    }
}
