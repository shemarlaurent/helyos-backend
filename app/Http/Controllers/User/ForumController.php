<?php

namespace App\Http\Controllers\User;

use App\Forum;
use App\ForumUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForumController extends Controller
{

    public function join(Forum $forum)
    {
        $payment = (new PaymentController)->payForForum($forum, auth()->user());
        ForumUser::create([
            'user_id' => auth()->id,
            'forum_id' => $forum->id,
        ]);
    }
}
