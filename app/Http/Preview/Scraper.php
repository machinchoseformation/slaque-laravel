<?php

namespace App\Http\Preview;

use App\Api\JsonResponse;
use App\Message;
use Symfony\Component\DomCrawler\Crawler;

final class Scraper
{

    private $guzzleClient;
    private $ext;
    private $response;
    private $message;
    private $crawler;
    private $link;

    public function __construct()
    {
        $this->guzzleClient = new \GuzzleHttp\Client(array(
            'timeout' => 10,
            'verify' => false,
            'proxy' => 'http://10.100.0.248:8080',
        ));
    }

    public function scrap(string $link, Message $message)
    {
        $this->link = $link;
        $this->message = $message;

        //check if it is an image, downloads it
        $this->response = $this->guzzleClient->get($this->link);
        if ($this->isImage()){
            return $this->downloadImage();
        }

        $this->crawler = new Crawler((string) $this->response->getBody());

        $titleNode = $this->crawler->filter('title')->first();
        $title = (count($titleNode)) ? $titleNode->html() : "";

        $descriptionNode = $this->crawler->filter('meta[name="description"]')->first();
        $description = (count($descriptionNode)) ? $descriptionNode->attr('content') : "";

        //favicon
        $favicon = $this->getFavicon();

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

    private function downloadImage()
    {
        $newFilename = md5(uniqid()) . "." . $this->ext;
        file_put_contents('img/groups/' . $newFilename, $this->response->getBody());

        //make sure it is an image
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, 'img/groups/' . $newFilename) . "\n";
        finfo_close($finfo);

        if (!preg_match("/^image\/(.+)/", $mime)) {
            //delete the file
            unlink('img/groups/' . $newFilename);
            $response = new JsonResponse([], 'not an image', 'error', 403);
            return $response->send();
        }

        $linkInfo = ['local_name' => $newFilename];
        $this->message->link_info = $linkInfo;
        $this->message->is_link_to_image = true;
        $this->message->save();

        $response = new JsonResponse($this->message);
        return $response->send();
    }

    private function isImage()
    {
        $contentTypes = $this->response->getHeader('Content-Type');
        if (count($contentTypes) > 0){
            $ct = $contentTypes[0];
            if (preg_match("/^image\/(.+)/", $ct, $matches)) {
                $this->ext = $matches[1];
                return true;
            }
        }

        return false;
    }

    private function getFavicon()
    {
        $urlComps = parse_url($this->link);
        $baseUrl = $urlComps['scheme'] . "://" . $urlComps['host'];

        $cssSelectors = [
            'link[rel="apple-touch-icon"]',
            'link[rel="icon"]',
            'link[rel="shortcut icon"]',
            'link[rel="favicon"]',
            'link[rel="x-icon"]',
        ];

        $favicon = null;
        foreach($cssSelectors as $sel){
            $faviconNode = $this->crawler->filter($sel)->first();
            $favicon = (count($faviconNode)) ? $faviconNode->attr('href') : null;
            if ($favicon){
                if (preg_match("/^\//", $favicon)){
                    $favicon = $baseUrl . $favicon;
                }
                return $favicon;
            }
        }

        return null;
    }
}