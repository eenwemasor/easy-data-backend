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
    public function initiate_electricity_transaction(array $data)
    {
        $token = config('constant.TOKEN');
        $url = config('constant.API_ENDPOINT')."/phcn/".$token."/".$data['company']."/".$data['type']."/".$data['meter_number']."/".$data['amount'];
        $client = new Client();

        try{

        $res = $client->request('POST', $url);
        return $res->getBody()->getContents();
        }catch (\Throwable $e) {
            throw new GraphqlError("Transaction failed, please try again: " . $e->getMessage());
        }
    }
}