<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06/04/2020
 * Time: 16:44
 */

namespace App\Repositories\APIRequests;


use App\GraphQL\Errors\GraphqlError;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ElectricityAPIRequests
{

    /**
     * @param array $data
     * @return mixed
     * @throws GraphqlError
     */
    public function initiate_electricity_transaction(array $data,$customer_name)
    {
        $url = config('constant.ELECTRICITY_END_POINT');
        $headers = config('constant.HEADERS');

        $response = json_encode([
            "message"=> "Transaction failed",
            "status"=> 404,
            "token"=>null,
            "disco"=>$data['disco'],
            "customerName"=>$customer_name,
            "transref"=> $data['request_id'],
            "date"=> Carbon::now(),
            "type"=> $data['type'],
            "amount"=>$data['amount'],
            "amountCharged"=> $data['amount']
        ]);

        $client = new Client($headers);
        $request_param = array_merge([
            'serviceCode' => "P-ELECT",
            'period'=>"1",
            'hasAddon'=>false
        ],$data);

        $request_data = $request_param;

        try{
            $res = $client->request('POST', $url, [['headers' => ['content-type' => 'application/json']],'form_params' => $request_data]);
            return $response = json_decode($res->getBody()->getContents());

        }catch (\Throwable $e){
            return json_decode($response);
        }
    }
}