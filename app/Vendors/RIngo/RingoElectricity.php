<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06/04/2020
 * Time: 16:44
 */

namespace App\Vendors\Ringo;


use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\PowerPlanList;
use App\User;

class RingoElectricity extends RingoRoot
{


    /**
     * @param array $args
     * @param PowerPlanList $powerPlanList
     * @param $reference
     * @param $amount
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function purchase_electricity(array $args, PowerPlanList $powerPlanList, $reference, $amount)
    {
        $request_param = array_merge([
            "serviceCode" => "P-ELECT",
            "disco" => $powerPlanList->disco,
            "meterNo" => $args['meter_number'],
            "type" => $args['type'],
            "amount" => $amount,
            "phonenumber" => $args['phone'],
            "request_id" => $reference]);
        error_log(json_encode([
            "serviceCode" => "P-ELECT",
            "disco" => $powerPlanList->disco,
            "meterNo" => $args['meter_number'],
            "type" => $args['type'],
            "amount" => $amount,
            "phonenumber" => $args['phone'],
            "request_id" => $reference]));
        $res = $this->client->request('POST', $this->url, [['headers' => ['content-type' => 'application/x-www-form-urlencoded']], 'form_params' => $request_param]);
        $response = json_decode($res->getBody()->getContents());
        if (str_upper($response->message) == "SUCCESSFUL" && $response->status == "200") {
            return [
                'success' => true,
                'message' =>"successful",
                'token' => $response->token
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
    public function validate_meter_number(array $args)
    {
        $request_param = array_merge([
            'serviceCode' => "V-ELECT",
            "disco" => $args['disco'],
            "meterNo" => $args['number'],
            "type" => "POSTPAID",
            "amount" => $args['amount'],
        ]);
        $requestResponse = [];

        $request_data = $request_param;
        $res = $this->client->request('POST', $this->url, [['headers' => ['content-type' => 'application/x-www-form-urlencoded']], 'form_params' => $request_data]);
        $response = json_decode($res->getBody()->getContents());

        if ($response->status == 200) {
            $requestResponse = [
                'name' => $response->customerName,
                'status' => "Success",
                'type' => $response->type,
                'message' => "Successful",
                'district' => $response->customerDistrict,
                'address' => $response->customerAddress
            ];
        } else {
            $requestResponse = [
                'status' => "Failed",
                'message' => $response->message,
            ];
        }
        error_log(json_encode($response));
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
            ServiceType::ELECTRICITY
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }
}
