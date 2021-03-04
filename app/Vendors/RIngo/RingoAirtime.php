<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/03/2020
 * Time: 23:20
 */

namespace App\Vendors\Ringo;


use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;

class RingoAirtime
{

    /**
     * @param array $data
     * @return mixed
     * @throws GraphqlError
     */
    public function initiate_airtime_transaction(array $data)
    {
        $url = config('constant.AIRTIME_DATA_END_POINT');
        $headers = config('constant.HEADERS');

        $client = new Client($headers);
        $request_param = array_merge([
            'serviceCode' => "VAR",
            'product_id' =>'MFIN-5-OR\''
        ],$data);

        $request_data = $request_param;
        $res = $client->request('POST', $url, [['headers' => ['content-type' => 'application/x-www-form-urlencoded']],'form_params' => $request_data]);
        $response = json_decode($res->getBody()->getContents());


        return $response;
    }


}
