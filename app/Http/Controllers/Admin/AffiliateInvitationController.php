<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InvitationController;
use App\Notifications\Admin\SellerInvitation;
use App\Notifications\Affiliate\AffiliateInvitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AffiliateInvitationController extends Controller
{
    /** send an invitation to become an Affiliate
     * @return JsonResponse
     */
    public function inviteAffiliate() : JsonResponse
    {
        $sellerEmail = request('email');

        // generate invitation token and send to the
        $invitation = (new InvitationController)
            ->sendInvitation($sellerEmail, 'affiliate');


        // send a notification to the seller
        Notification::route('mail', $sellerEmail)
            ->notify(new AffiliateInvitation($invitation->token));

        return response()->json($invitation);
    }
}
