<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class SendSMSController extends Controller
{
   function index(Request $request){
       $number = $request->phone;
       $message = $request->message;

       $senderid = 'Gtserviz';
       $to = $number;
       $token = 'xBhdoz1RXJ2Ib8GAdNqSRDvhoJ5LsbA6bPsQqYglwmn6zEa7qu6UZNrfvkmUTZ7TXW0dSXXNbLDxCfUMmLOAgBY2LHLo0kvPZhBP';
       $baseurl = 'https://smartsmssolutions.com/api/json.php?';

       $sms_array = array
       (
           'sender' => $senderid,
           'to' => $to,
           'message' => $message,
           'type' => '0',
           'routing' => 3,
           'token' => $token
       );

       $params = http_build_query($sms_array);
       $ch = curl_init();

       curl_setopt($ch, CURLOPT_URL,$baseurl);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

       $response = curl_exec($ch);

       curl_close($ch);

       return $response;
   }
}
