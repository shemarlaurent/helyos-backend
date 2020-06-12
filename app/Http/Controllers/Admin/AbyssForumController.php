<?php

namespace App\Http\Controllers\Admin;

use App\AbyssForum;
use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;

class AbyssForumController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required'
        ]);

        return response()->json(AbyssForum::create([
            'name' => $request->input('name'),
            'product_id' => $request->input('product_id'),
            'image' => $request->input('image')
        ]));
    }
}
