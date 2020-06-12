<?php

namespace App\Http\Controllers\User;

use App\Card;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //
    public function addCard($user, array $input)
    {
        $cardNumber = str_split($input['cardNumber']);

        Card::create([
            'user_id' => $user->id,
            'card_number' => $input['cardNumber'],
            'card_expiration' => implode('', explode('/', $input['cardExpiration'])),
            'card_cvv' => $input['cvv2'],
            'last_four' => implode('', array_slice($cardNumber, -4, 4))
        ]);
    }
}
