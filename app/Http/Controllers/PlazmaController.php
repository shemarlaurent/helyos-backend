<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlazmaController extends Controller
{
    public function shoeCheck(Request $request)
    {
        return response()->json('shoe');
    }

    public function shoeName(Request $request)
    {
        return response()->json(ucfirst('adidas yeezy'));
    }

    public function authentic(Request $request)
    {
        return response()->json('real');
    }

    public function soleCheck(Request $request)
    {
        return response()->json('new');
    }

}
