<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\Void_;

class InvitationController extends Controller
{
    /** generate and store an invitation token for sellers or affiliates
     * @param string $email
     * @param string $type
     * @return string
     */
    public function sendInvitation(string $email, string $type) : Model
    {
        $token = str_random(10);

        return Invitation::create([
            'email' => $email,
            'token' => $token,
            'type' => $type,
            'status' => false,
        ]);

    }

    /** check for valid invitation token
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token) : Boolean
    {
        $invitation = Invitation::where('token', $token)->first();

        if ($invitation) return true;

        return false;
    }

    /** This method marks an invitation as accepted
     * @param string $token
     */
    public function acceptInvitation(string $token) : void
    {
        $invitation = Invitation::where('token', $token)->first();

        if ($invitation) {
            // accept invitation;
            $invitation->status = true;

            $invitation->save();
        }
    }

    public function getInvitations()

    {
        return response()->json(Invitation::latest('created_at')->get());
    }
}
