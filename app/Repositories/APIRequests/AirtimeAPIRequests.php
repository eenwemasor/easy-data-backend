<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/03/2020
 * Time: 23:20
 */

namespace App\Repositories\APIRequests;


use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;

class AirtimeAPIRequests
{

    public function check_api_wallet_balance()
    {
    }

    public function check_service_status()
    {
    }

    /**
     * @param array $data
     * @return mixed
     * @throws GraphqlError
     */
    public function initiate_airtime_transaction(array $data)
    {
        $token = config('constant.TOKEN');
        $url = config('constant.API_ENDPOINT')."/airtime/".$token."/".str_lower($data['network'])."/".$data['phone']."/".$data['amount'];

        $client = new Client();
        try{
            $res = $client->request('POST', $url);
            $response = json_decode($res->getBody()->getContents());
            return $response;
        }catch (\Throwable $e) {
            throw new GraphqlError("Transaction failed, please try again: " . $e->getMessage());
        }
    }


}