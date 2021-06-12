<?php


namespace App\Services;

use App\User;
use GuzzleHttp\Client;
use HenryEjemuta\LaravelMonnify\Exceptions\MonnifyFailedRequestException;
use HenryEjemuta\LaravelMonnify\Facades\Monnify;


class MonnifyService
{
    public $curl;
    public $base_url;
    public function __construct()
    {
        $api_key = env("MONNIFY_API_KEY");
        $secret_key = env("MONNIFY_SECRET_KEY");
        $this->base_url = env("MONNIFY_BASE_URL");

        $this->curl = curl_init();

        curl_setopt( $this->curl , CURLOPT_USERPWD, $api_key . ":" . $secret_key);

    }


    public function authenticate()
    {
        $url = $this->base_url . "/auth/login/";
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
        );
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array($this->curl, $defaults);
        $response = curl_exec($this->curl);
        if(curl_errno($this->curl)){//If an error occured, throw an Exception.
            throw new Exception(curl_error($this->curl));
        }
       return json_decode($response)->responseBody->accessToken;
    }


    public function reserveAnAccount(array $user)
    {
        $request_data = [
            "accountName" => $user['full_name'],
            "accountReference" => $user['username'],
            "currencyCode" => "NGN",
            "contractCode" =>(integer) env("MONNIFY_CONTRACT_CODE"),
            "customerName" => $user['full_name'],
            "customerEmail" => $user['email'],
            "customerBVN" => null
        ];
        $url = $this->base_url . "/bank-transfer/reserved-accounts";
        $access_token = $this->authenticate();
        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_data),
        );

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array($this->curl, $defaults);
        $response = curl_exec($this->curl);
        if(curl_errno($this->curl)){
            throw new Exception(curl_error($this->curl));
        }

        return $response;

    }


    public function deleteReservedAccount(User $user)
    {
        $url = $this->base_url . "/bank-transfer/reserved-accounts/".$user->username;
        $access_token = $this->authenticate();
        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );
        $defaults = array(
            CURLOPT_URL => $url,
        );
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array($this->curl, $defaults);
        $response = curl_exec($this->curl);
        if(curl_errno($this->curl)){
            throw new Exception(curl_error($this->curl));
        }
        return $response;

    }




}
