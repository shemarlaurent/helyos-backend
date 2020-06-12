<?php

namespace App\Http\Controllers;

use App\Card;
use App\Product;
use App\Referral;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Laravel\Scout\Builder;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCard(Request $request)
    {
        $user = auth('api')->user();

        if ($user->card) {
            $user->card()->update([
                'card_number' => $request['number'],
                'card_expiration' => implode('', explode('/', $request['expiration'])),
                'card_cvv' => $request['cvv2'],
                'last_four' => implode('', array_slice(str_split($request['number']), -4, 4))
            ]);
        }
        else {
            $user->card()->create([
                'card_number' => $request['number'],
                'card_expiration' => implode('', explode('/', $request['expiration'])),
                'card_cvv' => $request['cvv2'],
                'last_four' => implode('', array_slice(str_split($request['number']), -4, 4))
            ]);
        }

        $user = User::with(['subscription', 'card'])->where('id', auth('api')->id())->first();

        return response()->json($user);

    }

    public function details()
    {
        $referrals = Referral::with('user')
            ->where('referable_id', auth('api')->id())
            ->get();


        $earning = auth('api')->user()->earnings;

        return response()->json([
            'referrals' => $referrals,
            'earning' => $earning
        ]);
    }
}
