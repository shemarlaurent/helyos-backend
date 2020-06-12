<?php

namespace App\Http\Controllers\Abyss;

use App\AbyssForum;
use App\Events\Abyss\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Validation\ValidationException;

class AbyssForumMessagesController extends Controller
{
    public function getMessages(AbyssForum $forum)
    {
        return response()->json($forum->messages);
    }
    /** post a message to a forum
     * @param Request $request
     * @param AbyssForum $forum
     * @return JsonResponse
     * @throws ValidationException
     */
    public function sendMessage(Request $request, AbyssForum $forum)
    {

        if ($request->input('text') ||$request->input('attachment') ) {
            $message = auth('abyss_user')->user()->messages()->create([
                'abyss_forum_id' => $forum->id,
                'text' => $request->input('text')
            ]);

            if ($request->input('attachment')) {
                // upload image attachment to cloudinary
                $attachment = $request->input('attachment');

                $message->attachment = $attachment;

                $message->save();
            }

            $response = $message->load(['user']);
            // broadcast a new event to the front end
            broadcast(new MessageSent($message));

            return response()->json($response);
        }


    }
}
