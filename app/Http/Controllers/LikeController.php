<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    /** method stores a like for a particular product
     * @param Request $request
     */
    public function like(Request $request)
    {
        return response()->json(Like::create([
            'user_id' => auth('api')->id(),
            'product_id' => $request->input('product_id'),
            'size' => $request->input('size')
        ]));
    }

    public function likes()
    {
        return response()->json(auth('api')->user()->likes->load('product'));
    }
}
