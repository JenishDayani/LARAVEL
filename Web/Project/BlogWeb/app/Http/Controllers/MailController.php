<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\DemoMail;

class MailController extends Controller
{
    public function index($email,$password)
    {
        $mailData = [
            'title' => 'Welcome to Blog😊',
            'body' => 'Account Created Successfully 🕺🏻🕺🏻',
            'email' => $email,
            'password' =>$password,
        ];

        Mail::to($email)->send(new DemoMail($mailData));

        return response()->json([
            'message' => 'Email Sent Successfully'
        ]);
    }
}
