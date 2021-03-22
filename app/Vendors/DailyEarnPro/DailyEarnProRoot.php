<?php

namespace App\Vendors\DailyEarnPro;

use App\GraphQL\Errors\GraphqlError;
use App\Vendors\ApplyAccountLevelApplicables;

class DailyEarnProRoot extends ApplyAccountLevelApplicables
{
    protected $apiToken;

    public function __construct()
    {
        $this->apiToken = config('constant.DAILY_EARN_PRO_TOKEN');
    }

    public function check_api_wallet($amount)
    {

        $url = 'https://dailyearnpro.com/api/balance?';
        $request = $this->compose_request([]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
        $response = curl_exec($ch);
        curl_close($ch);
        if (json_decode($response)->balance < $amount) throw new GraphqlError("Service is not available currently, please try again later");
    }


    protected function compose_request($param)
    {
        $request = "";
        foreach (array_merge(["apiToken" => $this->apiToken], $param) as $key => $val) //traverse through each member of the param array
        {
            $request .= $key . "=" . urlencode($val); //we have to urlencode the values
            $request .= '&'; //append the ampersand (&) sign after each paramter/value pair
        }
        $len = strlen($request) - 1;
        return substr($request, 0, $len); //remove the final ampersand sign from the request
    }
}
