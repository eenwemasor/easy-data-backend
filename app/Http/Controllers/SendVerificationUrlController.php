<?php

namespace App\Http\Controllers;
use App\Mail\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendVerificationUrlController extends Controller
{
    function sendVerificationLink(array  $data)
    {
        $url = null;
        $name= $data['name'];
        if(isset($data['url'])){
            $url = $data['url'];
        }else{
            $url = env('WEB_URL')."/email_verification/".$data['user_unique_id'];
        }
        $email = $data['email'];
        Mail::to($email)->send(new VerificationEmail($email, $url, $name));
    }


    public function sendEmailVerification(Request $request)
    {
        $data =  [
            'name'=> $request->name,
            'email'=> $request->email,
            'url'=> $request->url,
        ];
        $this->sendVerificationLink($data);
    }
}
