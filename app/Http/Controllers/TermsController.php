<?php

namespace App\Http\Controllers;

class TermsController extends Controller
{
    public function index()
    {
        seo('Terms and conditions');

        return view('terms');
    }
}
