<?php

//api urls for transaction
return [
    'MOBILE_NG_DECODER_USER_CHECK' => env('MOBILE_NG_DECODER_USER_CHECK'),
    'MOBILE_NG_API_KEY' => env('MOBILE_NG_API_KEY'),
    'MOBILE_NG_API_USERNAME' => env('MOBILE_NG_API_USERNAME'),

    'RINGO_ENDPOINT'=>env('RINGO_ENDPOINT'),
    'SERVICE_END_POINT' =>env('SERVICE_END_POINT'),

    //smart sms api constant
    'SMS_URL'=>env('SMS_URL'),
    'SENDER_ID'=>env('SENDER_ID'),
    'API_KEY'=>env('API_KEY'),

    'DAILY_EARN_PRO_TOKEN' => env('DAILY_EARN_API_TOKEN'),

    'HEADERS' =>['headers' => ['email' => env('RINGO_EMAIL'), 'password' => env('RINGO_PASSWORD')]]
];
