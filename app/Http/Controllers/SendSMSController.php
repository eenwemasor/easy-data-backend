<?php

namespace App\Http\Controllers;


use App\AdminChannelUtil;
use Illuminate\Http\Request;

class SendSMSController extends Controller
{
    function    sendSMS($message, $phone =null){
        $sender_id = config('constant.SENDER_ID');

        $to = $phone;

        if(!isset($to) ){
            $to = AdminChannelUtil::first()->phone;
        }

        $base_url = config('constant.SMS_URL');
        $ApiKey = config('constant.API_KEY');

        $sms_array = array
        (
            'from' => $sender_id,
            'to' => $to,
            'body' => $message,
            'api_token' => $ApiKey
        );

        $params = http_build_query($sms_array);
        try{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$base_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

            $response = curl_exec($ch);

            curl_close($ch);

            return $response;
        }catch (\Throwable $e){
            return true;
        }

    }


    function    sendBulkSMS($message, $to){
        $sender_id = config('constant.SENDER_ID');
        $base_url = config('constant.SMS_URL');
        $ApiKey = config('constant.API_KEY');

        $sms_array = array
        (
            'from' => $sender_id,
            'to' => $to,
            'body' => $message,
            'api_token' => $ApiKey
        );

        $params = http_build_query($sms_array);
        try{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$base_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

            $response = curl_exec($ch);

            curl_close($ch);

            return $response;
        }catch (\Throwable $e){
            return $e;
        }

    }


}
