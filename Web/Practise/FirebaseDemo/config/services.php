<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'firebase' => [
        'apiKey' => "AIzaSyD9VabtNMHm3tR_cZMLMeZ_Jwd5a_i51aw",
        'authDomain' => "laravel-first-demo.firebaseapp.com",
        'databaseURL' => "https://laravel-first-demo-default-rtdb.firebaseio.com/",
        'projectId' => "laravel-first-demo",
        'storageBucket' => "laravel-first-demo.appspot.com",
        'messagingSenderId' => "186051760671",
        'appId' => "1:186051760671:web:cb0e5b4951cb157b6cfb2a",
        'measurementId' => "G-64WSBHEGXG",
    ],

];
