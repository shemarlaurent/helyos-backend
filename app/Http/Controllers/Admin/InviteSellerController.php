<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InvitationController;
use App\Invitation;
use App\Notifications\Admin\SellerInvitation;
use App\Notifications\Affiliate\AffiliateInvitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use phpDocumentor\Reflection\Types\Boolean;

class InviteSellerController extends Controller
{
    public function __construct()
    {


    }

    /** send an invitation to become a seller
     * @return JsonResponse
     */
    public function inviteSeller() : JsonResponse
    {
        $sellerEmail = request('email');

        // generate invitation token and send to the
        $invitation = (new InvitationController)
            ->sendInvitation($sellerEmail, 'seller');


        // send a notification to the seller
        Notification::route('mail', $sellerEmail)
            ->notify(new SellerInvitation($invitation->token));

        return response()->json($invitation);
    }

    public function inviteAffiliate() : JsonResponse
    {
        $affiliate = request('email');

        // generate invitation token and send to the
        $invitation = (new InvitationController)
            ->sendInvitation($affiliate, 'affiliate');


        // send a notification to the seller
        Notification::route('mail', $affiliate)
            ->notify(new AffiliateInvitation($invitation->token));

        return response()->json($invitation);
    }
}
