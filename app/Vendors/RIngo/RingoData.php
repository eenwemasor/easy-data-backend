<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/03/2020
 * Time: 23:20
 */

namespace App\Vendors\Ringo;


use App\DataPlanList;
use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\User;
use GuzzleHttp\Client;

class RingoData extends RingoRoot
{


    /**
     * @param array $args
     * @param string $reference
     * @param DataPlanList $dataPlanList
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function purchase_data(array $args, string $reference, DataPlanList $dataPlanList)
    {
        $url = config('constant.RINGO_ENDPOINT');
        $headers = config('constant.HEADERS');
        $client = new Client($headers);
        $request_param = array_merge([
            'serviceCode' => "ADA",
            'msisdn' => $args['beneficiary'],
            'product_id'=> $dataPlanList->product_code,
            'request_id' => $reference
        ]);

        $request_data = $request_param;
        $res = $client->request('POST', $url, [['headers' => ['content-type' => 'application/x-www-form-urlencoded']], 'form_params' => $request_data]);
        $response = json_decode($res->getBody()->getContents());
        if (str_upper($response->message) == "SUCCESSFUL" && $response->status == "200") {
            return [
                'success' => true,
                'message' =>"successful"
            ];
        } else {
            return [
                'success' => false,
                'message' => $response->message
            ];
        }

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
            ServiceType::DATA_DIRECT
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }

}
