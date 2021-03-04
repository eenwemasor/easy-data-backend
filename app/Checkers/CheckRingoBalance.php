<?php


namespace App\Checkers;


use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;

class CheckRingoBalance
{
    /**
     * @return mixed
     * @throws GraphqlError
     */
    public function get_api_account_info()
    {
        $url = config('constant.AIRTIME_DATA_END_POINT');
        $headers = config('constant.HEADERS');

        $client = new Client($headers);
        $request_param = ['serviceCode' => "INFO"];
        $request_data = json_encode($request_param);
        $res = $client->request('POST', $url, [
            'headers' => ['content-type' => 'application/json'], 'body' => $request_data]);
        $response = json_decode($res->getBody()->getContents());
        $res = $response->wallet;


        if (str_upper($res->message) == "SUCCESSFUL" && $res->status == "200") {
            return $res->wallet;
        } else {
            throw new GraphqlError($res->message);
        }

    }

}
