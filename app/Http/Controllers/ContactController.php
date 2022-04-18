<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ContactUsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
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
            'email' => 'required|email',
            'name' => 'required|max:255',
            'message' => 'required|min:10|max:999',
        ]);

        // send notification
        $usersWithAdminPermission = User::permission('admin_received_contact_us')->get();
        Notification::send($usersWithAdminPermission, new ContactUsNotification($validated));

        return back()->with('message', 'message sent!');
    }
}
