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
                    ->with(['chapters'])
                    ->get()
                    ->random(9)
                    ->map(function( $temp ){
                        $temp->chapters = $temp->chapters->take(2);
                        return $temp;
                    });
        return view('home', compact('chapters', 'mangas'));
    }    
}
