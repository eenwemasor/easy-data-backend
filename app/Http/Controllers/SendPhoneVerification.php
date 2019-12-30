<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\PhoneVerification;
use App\User;
use Illuminate\Http\Request;

class SendPhoneVerification extends Controller
{


    function generateToken(Request $request){
        $username = 'sandbox'; // use 'sandbox' for development in the test environment
        $apiKey   = '567a99a94c41364e92ac589d0176e7ddbc3a2e8076b1efecbc67249aa50c2a30'; // use your sandbox app API key for development in the test environment
        $AT       = new AfricasTalking($username, $apiKey);
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

                if (strpos($phone, '0') === 0 && strlen($phone) === 11) {
                    $send_phone = substr($phone, 1);
                    $phone = "+234".$send_phone;
                }elseif(strpos($phone, '0') !== 0 && strlen($phone) === 10){
                    $phone = "+234".$phone;
                }
                        $message = "Gtserviz DeletePhoneToken Verification token " . $token;
                        $sms      = $AT->sms();

                        $result   = $sms->send([
                            'to'      => $phone,
                            'message' => $message
                        ]);
                return $result;

            }else{
                return response()->json(['message' => 'Token has already been sent', 'error_code'=>'201']);
            }
        }else{
            return response()->json(['message' => 'Phone does not exist', 'error_code'=>'202']);
        }
    }



    function resendToken(Request $request){
        $username = 'sandbox'; // use 'sandbox' for development in the test environment
        $apiKey   = '567a99a94c41364e92ac589d0176e7ddbc3a2e8076b1efecbc67249aa50c2a30'; // use your sandbox app API key for development in the test environment
        $AT       = new AfricasTalking($username, $apiKey);
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
                    $message = "Gtserviz DeletePhoneToken Verification token " . $token;
                    $sms      = $AT->sms();

                    $result   = $sms->send([
                        'to'      => $phone,
                        'message' => $message
                    ]);
                return $result;

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
            return response()->json(['message' => 'Phone Number Verified']);
        }else{
            return response()->json(['message' => 'wrong token or it has expired']);
        }
    }

}
