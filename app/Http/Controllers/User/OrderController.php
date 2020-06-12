<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(auth()->user()->orders()->with(['user', 'product_variation.product'])->get());
    }
}
