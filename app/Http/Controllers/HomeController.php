<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class HomeController extends Controller
{
    public function index()
    {
        $chapters = modelInstance('Chapter')->notInvalidLink()->orderByRelease()->simplePaginate(
            config('appsettings.home_chapters_entries')
        ); 

        $mangas = modelInstance('Manga')
                    ->with(['chapters' => function ($q) {
                        $q->orderBy('chapter', 'desc');
                        $q->notInvalidLink();
                    }])
                    ->get()
                    ->random(15)
                    ->map(function( $temp ){
                        $temp->chapters = $temp->chapters->take(2);
                        return $temp;
                    });
        
        $title = 'Home';
        $tempArray = [];
        $description = config('appsettings.app_slogan');
        $url = url()->current();
        $img = asset('images/WINEX2.png');
        $type = $title;

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

        return view('home', compact('chapters', 'mangas'));
    }
}
