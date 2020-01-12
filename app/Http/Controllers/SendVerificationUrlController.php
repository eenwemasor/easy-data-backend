<?php

namespace App\Http\Controllers;
use App\Mail\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendVerificationUrlController extends Controller
{
    function index(Request $request)
        {
            $name= $request->name;
            $url = $request->url;
            $email = $request->email;


            if(Mail::to($email)->send(new VerificationEmail($email, $url, $name))){
                return "done";
            }
        }
}
