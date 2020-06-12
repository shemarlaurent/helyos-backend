<?php

namespace App\Http\Controllers\Abyss;

use App\AbyssUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function update(Request $request)
    {
        // find the current user

        $abyss = AbyssUser::where('id', $request->input('id'))->first();

        $abyss->name = $request['name'];
        $abyss->country = $request->input('country');
        $abyss->city = $request->input('city');
        $abyss->state = $request->input('state');
        $abyss->address = $request->input('address');

        if ($request->input('oldPassword') && $request->input('newPassword')) {
            $abyss->password = bcrypt('newPassword');
        }


        $abyss->save();


        return response()->json($abyss);
    }
}
