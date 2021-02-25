<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06/04/2020
 * Time: 16:47
 */

namespace App\Repositories\APIRequests;


use App\GraphQL\Errors\GraphqlError;
use App\Services\CableTransactionService;
use GuzzleHttp\Client;
use Throwable;

class ValidateTransactions
{


    /**
     * @param string $phone
     * @return mixed
     * @throws GraphqlError
     */
    public function get_phone_vendor_details(string $phone)
    {
        $url = config('constant.AIRTIME_DATA_END_POINT');
        $headers = config('constant.HEADERS');

        $client = new Client($headers);
        $request_param = ['serviceCode' => "VDA", 'msisdn' => $phone];
        $request_data = json_encode($request_param);
        $res = $client->request('POST', $url, [
            'headers' => ['content-type' => 'application/json'], 'body' => $request_data]);
        $response = json_decode($res->getBody()->getContents());

        if ($response->message == "SUCCESSFUL" && $response->status == "200") {
            return $response->data;
        } else {
            throw new GraphqlError($response->message);
        }
    }

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

    public function get_bills_meter_details(array $data, $amount)
    {
        $api_wallet = $this->get_api_account_info();
        if($api_wallet->balance < $amount){
            throw new GraphqlError("Service is not available currently, please try again later");
        }

        try {
            $url = config('constant.CABLE_END_POINT');
            $headers = config('constant.HEADERS');

            $client = new Client($headers);
            $request_param = [
                'serviceCode' => 'V-ELECT',
                'disco'=>$data['disco'],
                'meterNo'=>$data['number'],
                'type'=>$data['type']
            ];
            $request_data = json_encode($request_param);
            $res = $client->request('POST', $url, ['headers' => ['content-type' => 'application/json'], 'body' => $request_data]);
            $response = json_decode($res->getBody()->getContents());

            return [
                'name'=>$response->customerName,
                'address'=> $response->customerAddress,
                'meter_number'=> $response->meterNo,
                'customer_district'=> $response->customerDistrict,
                'phone_number'=> $response->phoneNumber,
                'type'=> $response->type,
                'status'=> "SUCCESSFUL",
                'disco'=> $response->disco,
            ];

        } catch (Throwable $e) {
            return [
                'name'=> 'null',
                'address'=> 'null',
                'meter_number'=> 'null',
                'customer_district'=> 'null',
                'phone_number'=> 'null',
                'type'=> 'null',
                'status'=>"Failed to load meter details",
                'disco'=> 'null',
            ];

        }


    }

    static public function checkAvailableService($services, $service, $type, $description)
    {
        $service_type =  $service."_".$type;
        foreach ($services as $s){
            $product = $s->product;

            if($product === $service_type  && $s->status !== "Available"){
                throw  new GraphqlError($description." is currently not available, please try again later");
            }

        }
    }


}
