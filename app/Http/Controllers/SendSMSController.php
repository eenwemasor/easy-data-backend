<?php

namespace App\Http\Controllers;


use App\AdminChannelUtil;
use Illuminate\Http\Request;

class SendSMSController extends Controller
{
   function index(Request $request){
       $number = $request->phone;
       $message = $request->message;


        $response = $this->sendSMS($number, $message);

        return $response;

   }


    function sendSMS($message){
       $body = strval($message);
        $channel = AdminChannelUtil::findOrFail(1);
        $senderid = 'EasyData';
        $to = '08155400780';
        $token = 'xBhdoz1RXJ2Ib8GAdNqSRDvhoJ5LsbA6bPsQqYglwmn6zEa7qu6UZNrfvkmUTZ7TXW0dSXXNbLDxCfUMmLOAgBY2LHLo0kvPZhBP';
        $baseurl = 'https://smartsmssolutions.com/api/json.php?';

        $sms_array = array
        (
            'sender' => $senderid,
            'to' => $to,
            'message' => $body,
            'type' => '2',
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
        error_log($response);
        return $response;
    }
}
