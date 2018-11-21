<?php

namespace App\Http\Controllers;

use App\Api\JsonResponse;
use App\Group;
use App\GroupMessage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function delete(Request $request)
    {
        $message = GroupMessage::find($request->input('messageId'));

        if (Gate::denies('delete-message-in-group', $message)) {
            $response = new JsonResponse([], 'access denied', 'error', 403);
            return $response->send();
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
            $response = new JsonResponse([], 'access denied', 'error', 403);
            return $response->send();
        }

        $since = $request->input('since');

        $messages = GroupMessage::
            where('group_id', '=', $group->id)
            ->when($since, function($query, $since){
                return $query->where('created_at', '>', $since);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $response = new JsonResponse($messages);
        return $response->send();
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

        $message = GroupMessage::create([
            "content" => $request->input('message'),
            "group_id" => $groupId,
            "creator_id" => Auth::id(),
        ]);

        $response = new JsonResponse($message);
        return $response->send();
    }
}
