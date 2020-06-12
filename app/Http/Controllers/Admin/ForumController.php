<?php

namespace App\Http\Controllers\Admin;

use App\Forum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function createForum(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'min:2']
        ]);

        return response()->json(
            Forum::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ])
        );
    }
}
