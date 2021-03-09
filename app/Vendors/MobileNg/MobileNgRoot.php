<?php


namespace App\Vendors\MobileNg;


use App\GraphQL\Errors\GraphqlError;

class MobileNgRoot
{
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $api_key;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $username;

    /**
     * MobileNgTransaction constructor.
     */
    function __construct()
    {
        $this->api_key = config('constant.MOBILE_NG_API_KEY');
        $this->username = config('constant.MOBILE_NG_API_USERNAME');
    }

    /**
     * @param $amount
     * @throws GraphqlError
     */
    protected function get_account_info($amount)
    {
        $url = 'https://mobilenig.com/API/balance?';
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
        foreach (array_merge(["username" => $this->username, "api_key" => $this->api_key], $param) as $key => $val) //traverse through each member of the param array
        {
            $request .= $key . "=" . urlencode($val); //we have to urlencode the values
            $request .= '&'; //append the ampersand (&) sign after each paramter/value pair
        }
        $len = strlen($request) - 1;
        return substr($request, 0, $len); //remove the final ampersand sign from the request
    }
}
