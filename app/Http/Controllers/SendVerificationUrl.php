<?php

namespace App\Http\Controllers;
use App\Mail\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendVerificationUrl extends Controller
{
    function index(Request $request)

        {
            $name= $request->name;
            $url = $request->url;
            $email = $request->email;

            Mail::to($email)->send(new VerificationEmail($url, $email, $name));
        }
}
