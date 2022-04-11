<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $chapters = Chapter::notInvalidLink()->orderByRelease()->simplePaginate(
            config('appsettings.home_chapters_entries')
        ); 

        return view('home', compact('chapters'));
    }    
}
