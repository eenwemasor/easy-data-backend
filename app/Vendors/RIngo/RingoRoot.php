<?php


namespace App\Vendors\Ringo;


use App\GraphQL\Errors\GraphqlError;
use App\Vendors\ApplyAccountLevelApplicables;
use GuzzleHttp\Client;

class RingoRoot extends ApplyAccountLevelApplicables
{
    private $headers;
    public $client;
    public $url;
    public function __construct()
    {
        $this->headers = config('constant.HEADERS');
        $this->client = new Client($this->headers);
        $this->url = config('constant.RINGO_ENDPOINT');
    }

    /**
     * @param $transactionAmount
     * @throws GraphqlError
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function check_api_wallet($transactionAmount)
    {
        $url = config('constant.RINGO_ENDPOINT');;

        $request_param = ['serviceCode' => "INFO"];
        $request_data = json_encode($request_param);
        $res = $this->client->request('POST', $url, [
            'headers' => ['content-type' => 'application/json'], 'body' => $request_data]);
        $response = json_decode($res->getBody()->getContents());
        $res = $response->wallet;
        if (str_upper($res->message) == "SUCCESSFUL" && $res->status == "200") {
//            if ($res->wallet->balance < $transactionAmount) throw new GraphqlError("Service is not available currently, please try again later");
        } else {
            throw new GraphqlError($res->message);
        }
    }
}
