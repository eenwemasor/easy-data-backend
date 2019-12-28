<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;

class SendSMSController extends Controller
{
   function index(Request $request){
       $number = $request->phone;
       $message = $request->message;

       $username = 'sandbox'; // use 'sandbox' for development in the test environment
       $apiKey   = '567a99a94c41364e92ac589d0176e7ddbc3a2e8076b1efecbc67249aa50c2a30'; // use your sandbox app API key for development in the test environment
       $AT       = new AfricasTalking($username, $apiKey);


       $sms      = $AT->sms();

       $result   = $sms->send([
           'to'      => $number,
           'message' => $message
       ]);

       return $result;
   }
}
