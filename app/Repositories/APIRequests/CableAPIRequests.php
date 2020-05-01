<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/03/2020
 * Time: 10:08
 */

namespace App\Repositories\APIRequests;


use App\Contracts\TransactionValidationContract;
use App\Enums\TransactionStatus;
use App\GraphQL\Errors\GraphqlError;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CableAPIRequests
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
    public function initiate_cable_transaction(array $data)
    {
        $token = config('constant.TOKEN');

        $url = config('constant.API_ENDPOINT')."/tv/".$token."/".str_lower($data['cable'])."/".$data['smart_card_number']."/".$data['plan'];
        $client = new Client();
        try{
            $res = $client->request('POST', $url);
            return $res->getBody()->getContents();
        }catch (\Throwable $e){
            throw new GraphqlError("Transaction failed, please try again: ".$e->getMessage());
        }
    }

}