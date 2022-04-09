<?php

namespace App\Services;

use Goutte\Client;
use Illuminate\Support\Facades\Storage;

class GetProxyService
{
    protected $client;

    protected $proxy;

    protected $file;

    public function __construct()
    {
        $this->client = new Client();

        $this->file = public_path('storage/'. config('myproxy.filePathAndName'));

        if (!file_exists($this->file)) {
            // dump('downloading proxies...');
            // $this->download();
        }
        
        $this->proxy = $this->get();
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

    public function get($proxyCount = null) 
    {
        $handle = fopen($this->file, "r");
        
        $proxies = [];

        if ($handle) {

            $count = 0;

            while (($line = fgets($handle)) !== false) {

                $proxies[] = str_replace(array("\r", "\n"), '', $line);
                
                if ($proxyCount != null && $count == $proxyCount) {
                    break;
                }
            
                $count++;
            }// end while
        
            fclose($handle);
        } else {
            // error opening the file.
        }

        return $proxies;
    }// end get

    public function random()
    {
        $max = count($this->proxy) - 1;
        
        return $this->proxy[rand (0,$max)];
    }

}