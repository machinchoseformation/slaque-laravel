<?php

namespace App\Http\Controllers;

use App\Group;
use App\Message;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function delete(Request $request)
    {
        $message = Message::find($request->input('messageId'));

        if (Gate::denies('delete-message-in-group', $message)) {
            return response()->json([
                'error_message' => 'access denied',
                'status' => 'error',
                'data' => [],
            ])->setStatusCode(403);
        }

        $message->delete();

        return response()->json([
            'error_message' => null,
            'status' => 'ok',
            'data' => [],
        ]);
    }

    public function getSince($groupId, Request $request)
    {
        $group = Group::find($groupId);
        if (Gate::denies('read-group-messages', $group)) {
            return response()->json([
                'error_message' => 'access denied',
                'status' => 'error',
                'data' => [],
            ])->setStatusCode(403);
        }

        $since = $request->input('since');

        $messages = Message::
            where('group_id', '=', $group->id)
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
        $group = Group::find($groupId);

        if (Gate::denies('publish-message-in-group', $group)) {
            return response()->json([
                'error_message' => 'access denied',
                'status' => 'error',
                'data' => [],
            ])->setStatusCode(403);
        }

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
