<?php

namespace App\Http\Controllers;

class AboutUsController extends Controller
{
    public function index()
    {
        seo('About-Us');

        return view('about_us');
    }
}
