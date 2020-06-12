<?php

namespace App\Http\Controllers\Abyss;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\PaymentController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbyssUserForumController extends Controller
{
    /** return all active user forums
     * @return JsonResponse
     */
    public function getUserForums()
    {
        return response()->json(auth('abyss_user')
            ->user()
            ->forums()
            ->where('status', true)
            ->get());
    }


    /** join a forum
     * @param Request $request
     * @return JsonResponse
     */
    public function joinForum(Request $request)
    {

        if ((new PaymentController)->chargeAbyss(auth('abyss_user')->user())) {
            auth('abyss_user')->user()->joinForum(intval($request['forum_id']));

            return response()->json('payment successful');
        }

        else {
            return response()->json('payment not successful', 403);
        }

    }

}
