<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $chapters = Chapter::notInvalidLink()->orderByRelease()->simplePaginate(15); 

        return view('home', compact('chapters'));
    }    
}
