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
     * @var
     */
    private $api_key;
    /**
     * @var
     */
    private $user_id;

    /**
     * airtimeAPIRequests constructor.
     * @param $api_key
     * @param $user_id
     */
    function __construct($api_key, $user_id)
    {
        $this->api_key = $api_key;
        $this->user_id = $user_id;
    }

    /**
     * @param array $data
     * @param string $url
     * @return array
     * @throws
     */
    public function checkApiBalance(array $data,string $url): array
    {
    }

    /**
     * @param array $data
     * @param string $url
     * @return array
     *
     */
    public function checkServiceStatus(array $data ,string $url): array
    {
    }

    /**
 * @param array $data
 * @param string $url
 * @return array
 * @throws
 */
    public function InitiateTransaction(array $data, string $url): array
    {

    }



    /**
     * @param array $data
     * @param string $url
     * @return array
     * @throws
     */
    public function InitiateDataTransaction(array $data, string $url): array
    {
    }

}