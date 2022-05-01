<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class MangaController extends Controller
{
    public function show($slugOrId)
    {
        $manga = modelInstance('Manga')
                    ->where('id', $slugOrId)
                    ->orWhere('slug', $slugOrId)
                    ->firstOrFail();

        $chapters = modelInstance('Chapter')
                    ->where('manga_id', $manga->id)
                    ->notInvalidLink()
                    ->orderByRelease()
                    ->simplePaginate(
                        config('appsettings.home_chapters_entries')
                    );
        

        $title = $manga->title;
        $description = $manga->alternative_title;
        
        $tempArray = [];
        foreach ($chapters as $chapter) {
            $tempArray[] = 'Chapter '.$chapter->chapter;
        }

        $url = url()->current();
        $img = asset($manga->photo);
        $type = 'chapters';

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::addMeta('manga:published_time', $manga->created_at->toW3CString(), 'property');
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

        return view('manga', compact('manga', 'chapters'));       
    }
}
