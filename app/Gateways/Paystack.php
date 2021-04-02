<?php


namespace App\Gateways;


class Paystack
{
    private $url;
    private $header;

    public function __construct()
    {
        $this->url = env('PAYSTACK_URL');
        $this->header = array(
            "Authorization: Bearer ".env('paystack_secret_key'),
            "Cache-Control: no-cache",
        );

    }

    public function curl( $endpoint, $request_type)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url. $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $request_type,
            CURLOPT_HTTPHEADER => $this->header
        ));

        return $curl;
    }

    public function get_bank_list()
    {
        $curl  = $this->curl('bank', "GET");

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
           return json_decode($response)->data;
        }
    }

}
