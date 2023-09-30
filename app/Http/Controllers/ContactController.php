<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Throttle;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use App\Notifications\ContactUsNotification;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
        $title = 'Contact Us';
        $tempArray = [];
        $description = config('appsettings.app_slogan');
        $url = url()->current();
        $img = asset('images/winexhub.png');
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

        return view('contact');
    }

    /**
     * Store a new blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated =  $request->validate([
            // 'email' => 'required|email',
            'email' => [
                'required',
                'email',
                new Throttle('contact-form', $maxAttempts = 5, $minutes = 10), 
            ],
            'name' => 'required|max:255',
            'message' => 'required|min:10|max:999',
            'g-recaptcha-response' => 'recaptcha',
        ]);

        if (auth()->check()) {
            $validated['auth_user'] = auth()->user()->id;
        }
        
        // dont include captcha in notification
        unset($validated['g-recaptcha-response']);

        // send notification
        $usersWithAdminPermission = User::permission('admin_received_contact_us')->get();
        Notification::send($usersWithAdminPermission, new ContactUsNotification($validated));

        return back()->with('message', 'message sent!');
    }
}
