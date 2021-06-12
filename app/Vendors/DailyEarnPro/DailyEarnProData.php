<?php

namespace App\Vendors\DailyEarnPro;

use App\DataPlanList;
use App\Enums\NetworkType;
use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\User;

class DailyEarnProData extends DailyEarnProRoot
{
    /**
     * @param array $args
     * @param string $reference
     * @param DataPlanList $dataPlanList
     * @throws GraphqlError
     */
    public function purchase_data(array $args, string $reference, DataPlanList $dataPlanList)
    {
        $requestResponse = [];
        $url = $this->get_url($dataPlanList->network);
        $request = $this->compose_request([
            "network" => $dataPlanList->network === NetworkType::GLO ? "GLO" : $dataPlanList->network,
            "mobile" => $dataPlanList->network,
            "network_code" => $dataPlanList->product_code,
            'ref' => $reference
        ]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
        $response = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            $requestResponse = [
                "success" => false,
                "message" => curl_error($ch)
            ];
        } else {
            if ($response->code === "200") {
                $requestResponse = [
                    "success" => true,
                    "message" => "successful"
                ];
            } else {
                $requestResponse = [
                    "success" => false,
                    "message" => $response->desc
                ];
            }
        }

        curl_close($ch);
        return $requestResponse;
    }

    /**
     * @param $network
     * @return string
     * @throws GraphqlError
     */
    private function get_url($network)
    {
        switch ($network) {
            case NetworkType::MTN:
                return "https://dailyearnpro.com/api/mtn?";
            case NetworkType::GLO:
                return "https://dailyearnpro.com/api/glo?";
            case NetworkType::NINE_MOBILE:
                return "https://dailyearnpro.com/api/9mobile?";
            case NetworkType::AIRTEL:
                return "https://dailyearnpro.com/api/airtel?";
            default:
                throw new GraphqlError("Invalid network type");
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
            ServiceType::DATA_SME
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }

}
