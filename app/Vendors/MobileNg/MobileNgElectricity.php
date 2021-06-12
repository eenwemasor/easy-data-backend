<?php


namespace App\Vendors\MobileNg;


use App\GraphQL\Errors\GraphqlError;
use GuzzleHttp\Client;
use Throwable;

class MobileNgElectricity extends MobileNgRoot
{

    public function get_bills_meter_details(array $data)
    {
//        $api_wallet = $this->get_api_account_info();
//        if($api_wallet->balance < $amount){
//            throw new GraphqlError("Service is not available currently, please try again later");
//        }

        try {
            $url = config('constant.RINGO_ENDPOINT');
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

}
