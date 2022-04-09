<?php

namespace App\Services;

use Goutte\Client;
use Illuminate\Support\Facades\Storage;

class GetProxyService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function download()
    {
        $url = config('myproxy.url');
        $selectLink = config('myproxy.selectLink');
        $fileName = config('myproxy.filePathAndName');

        $crawler = $this->client->request('GET', $url);
        $link = $crawler->selectLink($selectLink)->link();
        $link = $link->getUri();
        $contents = file_get_contents($link);


        $temp = Storage::disk('public')->put($fileName, $contents);
    
        return $temp;
    }

}