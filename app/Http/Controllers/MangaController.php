<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    public function show(Manga $manga)
    {
        $chapters = modelInstance('Chapter')
                    ->where('manga_id', $manga->id)
                    ->notInvalidLink()
                    ->orderByRelease()
                    ->simplePaginate(
                        config('appsettings.home_chapters_entries')
                    );
        
        seo($manga->title);

        return view('manga', compact('manga', 'chapters'));       
    }
}
