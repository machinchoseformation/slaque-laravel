<?php

namespace App\Http\Controllers;

use App\Api\JsonResponse;
use App\Group;
use App\GroupMessage;

use Goutte\Client;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\DomCrawler\Crawler;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function delete(Request $request)
    {
        $message = GroupMessage::find($request->input('messageId'));

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

        $content = $request->input('message');

        $isLink = (filter_var($content, FILTER_VALIDATE_URL)) ? true : false;

        $message = GroupMessage::create([
            "content" => $content,
            "group_id" => $groupId,
            "creator_id" => Auth::id(),
            "is_link" => $isLink
        ]);

        $response = new JsonResponse($message);
        return $response->send();
    }

    public function linkPreview(Request $request){
        $link = $request->input('link');
        $messageId = $request->input('messageId');

        $message = GroupMessage::find($messageId);

        $goutteClient = new Client();
        $guzzleClient = new \GuzzleHttp\Client(array(
            'timeout' => 10,
            'verify' => false,
            'proxy' => 'http://10.100.0.248:8080',
        ));

        $goutteClient->setClient($guzzleClient);
        $crawler = $goutteClient->request('GET', $link);

        $urlComps = parse_url($link);
        $baseUrl = $urlComps['scheme'] . "://" . $urlComps['host'];

        $titleNode = $crawler->filter('title')->first();
        $title = (count($titleNode)) ? $titleNode->html() : "";

        $descriptionNode = $crawler->filter('meta[name="description"]')->first();
        $description = (count($descriptionNode)) ? $descriptionNode->attr('content') : "";

        //favicon
        $faviconNode = $crawler->filter('link[rel="apple-touch-icon"]')->first();
        $favicon = (count($faviconNode)) ? $faviconNode->attr('href') : null;

        if (!$favicon){
            $faviconNode = $crawler->filter('link[rel="icon"]')->first();
            $favicon = (count($faviconNode)) ? $faviconNode->attr('href') : null;
        }
        if (!$favicon){
            $faviconNode = $crawler->filter('link[rel="shortcut icon"]')->first();
            $favicon = (count($faviconNode)) ? $faviconNode->attr('href') : null;
        }

        if (!$favicon){
            $faviconNode = $crawler->filter('link[rel="favicon"]')->first();
            $favicon = (count($faviconNode)) ? $faviconNode->attr('href') : null;
        }

        if (preg_match("/^\//", $favicon)){
            $favicon = $baseUrl . $favicon;
        }

        $linkInfo = [
            'title' => $title,
            'description' => $description,
            'favicon' => $favicon
        ];

        $message->link_info = json_encode($linkInfo);
        $message->save();

        $response = new JsonResponse($linkInfo);
        return $response->send();
    }
}
