<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/03/2020
 * Time: 23:20
 */

namespace App\Repositories\APIRequests;


use App\Contracts\TransactionValidationContract;
use App\Enums\NetworkType;
use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Monolog\Logger;

class DataAPIRequests
{

    /**
     * @param array $data
     * @return mixed
     * @throws GraphqlError
     */
    public function InitiateDataTransaction(array $data)
    {

    }

}