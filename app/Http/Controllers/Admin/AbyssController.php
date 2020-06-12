<?php

namespace App\Http\Controllers\Admin;

use App\AbyssForum;
use App\AbyssUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbyssController extends Controller
{
    public function details()
    {
        $details = AbyssForum::with('users')->get();

        $users = AbyssUser::all();

        return response()->json([
            'details' => $details,
            'users' => $users
        ]);
    }
}
