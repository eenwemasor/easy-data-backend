<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/03/2020
 * Time: 10:08
 */

namespace App\Vendors\Ringo;


use App\Contracts\TransactionValidationContract;
use App\Enums\TransactionStatus;
use App\GraphQL\Errors\GraphqlError;
use Carbon\Carbon;
use GuzzleHttp\Client;

class RingoCable
{
    public function check_api_wallet_balance()
    {
    }

    public function check_service_status()
    {
    }

    /**
     * @param array $data
     * @param $amount
     * @return mixed
     */
    public function initiate_cable_transaction(array $data,$amount)
    {
        $url = config('constant.CABLE_END_POINT');
        $headers = config('constant.HEADERS');

        $response = json_encode([
            "message"=> "Transaction failed",
            "status"=> 404,
            "transref"=> $data['request_id'],
            "date"=> Carbon::now(),
            "type"=> $data['type'],
            "package"=> $data['name'],
            "amount"=>$amount,
            "amountCharged"=> $amount
        ]);

        $client = new Client($headers);
        $request_param = array_merge([
            'serviceCode' => "P-TV",
            'period'=>"1",
            'hasAddon'=>false,
            'price'=>$amount
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
