<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06/04/2020
 * Time: 16:47
 */

namespace App\Repositories\APIRequests;


use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;
use Throwable;

class ValidateTransactions
{
    /**
     * @return mixed
     * @throws GraphqlError
     */
    public function get_api_account_info()
    {
        $token = config('constant.TOKEN');
        $url = config('constant.API_ENDPOINT').'bal/'.$token;
        $client = new Client();
        $res = $client->request('POST', $url);
        $response = json_decode($res->getBody()->getContents());

        if ($res->getStatusCode() == 200) {
            return $response;
        } else {
            throw new GraphqlError("Internal Error Please try a again later");
        }

    }
}