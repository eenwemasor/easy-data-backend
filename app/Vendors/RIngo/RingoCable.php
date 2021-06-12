<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/03/2020
 * Time: 10:08
 */

namespace App\Vendors\Ringo;


use App\CablePlanList;
use App\Contracts\TransactionValidationContract;
use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;

class RingoCable extends RingoRoot
{
    /**
     * @param array $args
     * @param CablePlanList $cablePlanList
     * @param $reference
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function purchase_cable_tv(array $args, CablePlanList $cablePlanList, $reference)
    {
       $request_param = array_merge([
           "serviceCode" => "P-TV",
            "type" => $cablePlanList->cable,
            "smartCardNo" => $args['decoder_number'],
            "name" => $cablePlanList->plan,
            "code"=> $cablePlanList->product_code,
            "period"=> "1",
            "request_id"=> $reference,
            "hasAddon" => "False"
        ]);

        $request_data = $request_param;

        $res = $this->client->request('POST', $this->url, [['headers' => ['content-type' => 'application/json']], 'form_params' => $request_data]);
        $response =  json_decode($res->getBody()->getContents());
        if (str_upper($response->message) == "SUCCESSFUL" && $response->status == "200") {
            return [
                'success' => true,
                'message' =>"successful"
            ];
        } else {
            return [
                'success' => false,
                'message' =>$response->message
            ];
        }
    }


    /**
     * @param array $args
     */
    public function validate_smart_card(array $args)
    {
        $request_param = array_merge([
            'serviceCode' => "V-TV",
            'type' => $args['service'],
            'smartCardNo' => $args['number']
        ]);
        $requestResponse = [];

        $request_data = $request_param;
        $res = $this->client->request('POST', $this->url, [['headers' => ['content-type' => 'application/x-www-form-urlencoded']], 'form_params' => $request_data]);
        $response = json_decode($res->getBody()->getContents());

        if ($response->status == 200) {
            $requestResponse=[
                'name' => $response->customerName,
                'status' =>"Success",
                'type' => $response->type,
                'message' => $response->message
            ];
        } else {
            $requestResponse = [
                'status' => "Failed",
                'message' => $response->message
            ];
        }

        return $requestResponse;

    }

    /**
     * @param $data
     * @param $amount
     * @return float|int
     */
    public function apply_discount($data, $amount)
    {
        $user = User::find($data['user_id']);
        $applicables = $user->account_level->applicables()->where('service_type',
            ServiceType::CABLE
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }
}
