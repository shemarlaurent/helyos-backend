<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DelphiController extends Controller
{
    public function forecast(Request $request)
    {
        $price = (intval($request['size']) + intval($request['day'])) * 100;
        return response()->json($price);
    }
}
