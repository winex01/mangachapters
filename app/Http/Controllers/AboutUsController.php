<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class AboutUsController extends Controller
{
    public function index()
    {
        $title = 'About-Us';
        $tempArray = [];
        $url = url()->current();
        $img = asset('images/winexhub.png');
        $type = $title;
        $description = 'Hi, I’m Winnie the creator of '.config('app.name').'.';
        $description .= ' The main purpose of this website is to help people like me';
        $description .= ' who read Manga/Manhwa/Manhua across multiple different website to';
        $description .= ' manage all in one place. By bookmarking the Manga/Manhwa/Manhua you will';
        $description .= ' get notified once a new chapter is release.';
        $description .= ' Disclaimer, this is not a website where you can read Manga/Manhwa/Manhua it only redirects';
        $description .= ' you to the source of the new release or updates. Soo it\'s more of like a blog/news that notifies you';
        $description .= ' everytime a new chapter is realease.';

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

        return view('about_us');
    }
}
