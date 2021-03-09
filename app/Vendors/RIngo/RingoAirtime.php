<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/03/2020
 * Time: 23:20
 */

namespace App\Vendors\Ringo;
use App\Enums\NetworkType;
use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\User;
use GuzzleHttp\Client;

class RingoAirtime extends RingoRoot
{

    /**
     * @param array $data
     * @return mixed
     * @throws GraphqlError
     */
    public function initiate_airtime_transaction(array $data)
    {
        $url = config('constant.RINGO_ENDPOINT');
        $request_param = array_merge([
            'serviceCode' => "VAR",
        ], $data);

        $request_data = $request_param;
        $res = $this->client->request('POST', $url, [['headers' => ['content-type' => 'application/x-www-form-urlencoded']], 'form_params' => $request_data]);
        $response = json_decode($res->getBody()->getContents());

        return $response;
    }

    public function apply_discount($data)
    {
        $user = User::find($data['user_id']);
        $applicables = $user->account_level->applicables()->where('service_type',
            $this->get_service_type($data['network'])
        )->get();
       return $this->apply_applicable($data['amount'], $applicables);
    }


    /**
     * @param $network
     * @return string
     * @throws GraphqlError
     */
    private function get_service_type($network)
    {
        switch ($network){
            case NetworkType::AIRTEL:
                return ServiceType::AIRTEL_AIRTIME;
            case NetworkType::NINE_MOBILE:
                return ServiceType::ETISALAT_AIRTIME;
            case NetworkType::GLO:
                return ServiceType::GLO_AIRTIME;
            case NetworkType::MTN:
                return ServiceType::MTN_AIRTIME;
            default:
                throw new GraphqlError('Invalid network type');
        }
    }

    /**
     * @param $network
     * @return string
     * @throws GraphqlError
     */
    public function get_service_code($network)
    {
        switch ($network){
            case NetworkType::AIRTEL:
                return "MFIN-1-OR";
            case NetworkType::NINE_MOBILE:
                return "MFIN-2-OR";
            case NetworkType::GLO:
                return "MFIN-6-OR";
            case NetworkType::MTN:
                return "MFIN-5-OR";
            default:
                throw new GraphqlError('Invalid network type');
        }
    }
}
