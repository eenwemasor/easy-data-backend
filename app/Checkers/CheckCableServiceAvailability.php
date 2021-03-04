<?php


namespace App\Checkers;


use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;

class CheckCableServiceAvailability
{
    public function get_available_services($serviceCode)
    {

        $url = config('constant.SERVICE_END_POINT');
        $headers = config('constant.HEADERS');

        $client = new Client($headers);
        $request_param = ['serviceCode' => $serviceCode];
        $request_data = json_encode($request_param);
        $res = $client->request('POST', $url, ['headers' => ['content-type' => 'application/json'], 'body' => $request_data]);
        $response = json_decode($res->getBody()->getContents());

        return $response;
    }

//    static public function checkAvailableService($services,$service)
//    {
//        foreach ($services as $s){
//            $product = $s->product;
//
//            if($product === $service  && $s->status !== "Available"){
//                throw  new GraphqlError($service." is currently not available, please try again later");
//            }
//
//        }
//    }
}
