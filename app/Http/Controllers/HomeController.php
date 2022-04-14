<?php

namespace App\Http\Controllers;


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
        
        return view('home', compact('chapters', 'mangas'));
    }    
}
