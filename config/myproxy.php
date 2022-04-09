<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config values
    |--------------------------------------------------------------------------
    |
    | This file is for some configuration
    |
    */

    // the url where i will download my proxy file using Goutte\Client
    'url'               => 'https://proxyscrape.com/free-proxy-list',

    // the button label 
  	'selectLink'      => 'HTTP Proxies',

    // file name
  	'filePathAndName' => 'proxy/http_proxies.txt', //* DL it here: https://proxyscrape.com/free-proxy-list
    
];
