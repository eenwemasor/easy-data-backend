<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CableTvPurchase extends Controller
{

    function index(){

        $request = "";
        $param["username"] = "rajdolla";
        $param["api_key"] = "b0cc502b2413103a92e74379f07e8cbb";
        $param["service"] = "DSTV";
        $param["number"] = "4131953321";

        foreach($param as $key=>$val)
        {
            $request .= $key . "=" . urlencode($val);
            $request .= '&';
        }
        $len = strlen($request) - 1;
        $request = substr($request, 0, $len);
        $url = "https://mobilenig.com/API/airtime_test?";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $response = curl_exec($ch);
        curl_close($ch);

        Echo $response;

    }

}
