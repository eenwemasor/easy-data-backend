<?php

namespace App\Http\Controllers;

use App\PhoneVerification;
use App\User;
use Illuminate\Http\Request;

class SendPhoneVerification extends Controller
{
    function generateToken(Request $request){
        $phone = $request->phone;
        $phone_user = User::where('phone', $phone)->first();

        if($phone_user){

            $phone_token_exists = PhoneVerification::where('phone', $phone)->first();

            if(!$phone_token_exists){
                $token = mt_rand(100000, 999999);
                $phoneVerification = new PhoneVerification();
                $phoneVerification->phone = $phone;
                $phoneVerification->token = $token;
                $phoneVerification->save();
                $message = "subpay phone verification token " . $token;

                $sender_id = config('constant.SENDER_ID');
                $to = $phone;
                $ApiKey = config('constant.API_KEY');
                $base_url = config('constant.SMS_URL');

                $sms_array = array
                (
                    'from' => $sender_id,
                    'to' => $to,
                    'body' => $message,
                    'api_token' => $ApiKey
                );

                $params = http_build_query($sms_array);
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL,$base_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

                $response = curl_exec($ch);

                curl_close($ch);

                return $response;

            }else{
                return response()->json(['message' => 'Token has already been sent', 'error_code'=>'201']);
            }
        }else{
            return response()->json(['message' => 'Phone does not exist', 'error_code'=>'202']);
        }
    }

    function resendToken(Request $request){
        $phone = $request->phone;
        $phone_user = User::where('phone', $phone)->first();

        if($phone_user){

            $phone_token_exists = PhoneVerification::where('phone', $phone)->first();
            if(!$phone_token_exists){
            }else{
                    $phone_token_exists->delete();
                    $token = mt_rand(100000, 999999);
                    $phoneVerification = new PhoneVerification();
                    $phoneVerification->phone = $phone;
                    $phoneVerification->token = $token;
                    $phoneVerification->save();

                    if (strpos($phone, '0') === 0 && strlen($phone) === 11) {
                        $send_phone = substr($phone, 1);
                        $phone = "+234".$send_phone;
                    }elseif(strpos($phone, '0') !== 0 && strlen($phone) === 10){
                        $phone = "+234".$phone;
                    }
                    $message = "subpay phone verification token " . $token;
                $sender_id = config('constant.SENDER_ID');
                $to = $phone;
                $ApiKey = config('constant.API_KEY');
                $base_url = config('constant.SMS_URL');

                $sms_array = array
                (
                    'from' => $sender_id,
                    'to' => $to,
                    'body' => $message,
                    'api_token' => $ApiKey
                );

                $params = http_build_query($sms_array);
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL,$base_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

                $response = curl_exec($ch);

                curl_close($ch);

                return $response;

            }
        }else{
            return response()->json(['message' => 'Phone does not exist', 'error_code'=>'202']);
        }
    }

    function verifyToken(Request $request){
        $token = $request->token;
        $phone_token_exists = PhoneVerification::where('token', $token)->first();

        if($phone_token_exists){
            $phone_user = User::where('phone', $phone_token_exists->phone)->first();
            $phone_user->phone_verified = true;
            $phone_user->save();
            return response()->json(['message' => 'Phone number verified']);
        }else{
            return response()->json(['message' => 'Wrong token or it has expired']);
        }
    }

}
