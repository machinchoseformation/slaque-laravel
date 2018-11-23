<?php

namespace App\Http\Controllers;

use App\Api\JsonResponse;
use App\Group;
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

    public function linkPreview(Request $request){
        $link = $request->input('link');
        $messageId = $request->input('messageId');

        $message = Message::find($messageId);

        $goutteClient = new Client();
        $guzzleClient = new \GuzzleHttp\Client(array(
            'timeout' => 10,
            'verify' => false,
            'proxy' => 'http://10.100.0.248:8080',
        ));

        //check if it is an image, downloads it
        $r = $guzzleClient->get($link);
        $contentTypes = $r->getHeader('Content-Type');
        if (count($contentTypes) > 0){
            $ct = $contentTypes[0];
            if (preg_match("/^image\/(.+)/", $ct, $matches)){
                $ext = $matches[1];
                $newFilename = md5(uniqid()) . "." . $ext;
                file_put_contents('img/groups/' . $newFilename, $r->getBody());

                //make sure it is an image
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, 'img/groups/' . $newFilename) . "\n";
                finfo_close($finfo);

                if (!preg_match("/^image\/(.+)/", $ct)) {
                    //delete the file
                    unlink('img/groups/' . $newFilename);
                    $response = new JsonResponse([], 'not an image', 'error', 403);
                    return $response->send();
                }

                $linkInfo = ['local_name' => $newFilename];
                $message->link_info = $linkInfo;
                $message->is_link_to_image = true;
                $message->save();

                $response = new JsonResponse($message);
                return $response->send();
            }
        }

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

        $message->link_info = $linkInfo;
        $message->save();

        $message->refresh();

        $response = new JsonResponse($message);
        return $response->send();
    }
}
