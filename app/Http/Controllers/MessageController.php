<?php

namespace App\Http\Controllers;

use App\Group;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function getSince($groupId, Request $request)
    {
        //@todo: check for permissions !!! allowed to retrieve messages from this group ?

        $since = $request->input('since');

        $messages = Message::
            where('group_id', '=', $groupId)
            ->when($since, function($query, $since){
                return $query->where('created_at', '>', $since);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'error_message' => null,
            'status' => 'ok',
            'data' => $messages,
        ]);
    }

    public function create($groupId, Request $request)
    {
        //@todo: check for permissions !!! allowed to post in this gruop ?

        $message = Message::create([
            "content" => $request->input('message'),
            "group_id" => $groupId,
            "creator_id" => Auth::id(),
        ]);

        return response()->json([
            'error_message' => null,
            'status' => 'ok',
            'data' => $message,
        ]);
    }
}
