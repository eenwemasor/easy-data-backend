<?php

//api urls for transaction
return [
    'MOBILE_NG_DECODER_USER_CHECK' => env('MOBILE_NG_DECODER_USER_CHECK'),
    'MOBILE_NG_API_KEY' => env('MOBILE_NG_API_KEY'),
    'MOBILE_NG_API_USERNAME' => env('MOBILE_NG_API_USERNAME'),

    'AIRTIME_DATA_END_POINT'=>env('AIRTIME_DATA_END_POINT'),
    'BILLS_END_POINT'=>env('BILLS_END_POINT'),
    'SERVICE_END_POINT' =>env('SERVICE_END_POINT'),
    'CABLE_END_POINT' =>env('CABLE_END_POINT'),
    'ELECTRICITY_END_POINT' =>env('ELECTRICITY_END_POINT'),

    //smart sms api constant
    'SMS_URL'=>env('SMS_URL'),
    'SENDER_ID'=>env('SENDER_ID'),
    'API_KEY'=>env('API_KEY'),


    'HEADERS' =>['headers' => ['email' => env('RINGO_EMAIL'), 'password' => env('RINGO_PASSWORD')]]
];
