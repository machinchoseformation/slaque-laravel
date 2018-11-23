<?php

namespace App\Http\Controllers;

use App\Api\JsonResponse;
use App\Group;
use App\Http\Preview\Scraper;
use App\Message;

use Goutte\Client;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\DomCrawler\Crawler;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function delete(Request $request)
    {
        $message = Message::find($request->input('messageId'));

        if (Gate::denies('delete-message-in-group', $message)) {
            $response = new JsonResponse([], 'access denied', 'error', 403);
            return $response->send();
        }

        $message->deleted = 1;
        $message->updated_at = new \DateTime();
        $message->save();

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

        $messages = Message::
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

        $content = $request->input('message');

        $isLink = (filter_var($content, FILTER_VALIDATE_URL)) ? true : false;

        $message = Message::create([
            "content" => $content,
            "group_id" => $groupId,
            "creator_id" => Auth::id(),
            "is_link" => $isLink
        ]);

        $response = new JsonResponse($message);
        return $response->send();
    }

    public function linkPreview(Request $request, Scraper $scraper){
        $link = $request->input('link');
        $messageId = $request->input('messageId');

        $message = Message::find($messageId);
        $response = $scraper->scrap($link, $message);
        return $response;
    }
}
