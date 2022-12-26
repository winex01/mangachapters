<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class TermsController extends Controller
{
    public function index()
    {
        $title = 'Terms and conditions';
        $tempArray = [];
        $url = url()->current();
        $img = asset('images/winexhub.png');
        $type = $title;
        $description = 'Welcome aboard.';
        $description .= ' Welcome to '.config('app.name').'.';
        $description .= ' If you continue to browse and use this website you are agreeing to comply with and be bound by the following terms and conditions of use, The use of this website is subject to the following terms of use:';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setCanonical($url);
        SEOMeta::addKeyword($tempArray);

        OpenGraph::setDescription($description);
        OpenGraph::setTitle($title);
        OpenGraph::setUrl($url);
        OpenGraph::addProperty('type', $type);
        OpenGraph::addImage($img, ['height' => 300, 'width' => 300]);

        TwitterCard::setTitle($title);
        TwitterCard::setSite(config('appsettings.app_twitter'));

        TwitterCard::setDescription($description); // description of twitter card tag
        TwitterCard::setType($type); // type of twitter card tag
        TwitterCard::addValue('type', $type); // value can be string or array
        TwitterCard::setUrl($url); // url of twitter card tag
        TwitterCard::setImage($img); // add image url

        return view('terms');
    }
}
