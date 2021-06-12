<?php


namespace App\Vendors\MobileNg;


use App\Enums\ResultCheckerExamBody;
use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\User;

class MobileNgSpectranet extends MobileNgRoot
{
    /**
     * @param $spectranetPackage
     * @param $reference
     * @return mixed
     */
    public function purchase_spectranet($spectranetPackage, $reference, $args)
    {
        $url ="https://mobilenig.com/API/bills/spectranet?";
        $param = [
            "product_code" => $spectranetPackage->product_code,
            "price" => $spectranetPackage->vendor_price,
            "trans_id" => $reference,
        ];
        $request = $this->compose_request($param);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
        $requestResponse = [];
        $response = curl_exec($ch);
        $res = json_decode($response);
        if (curl_errno($ch)) {
            $requestResponse = [
                'success' => false,
                'message' =>curl_error($ch)
            ];
        } else {
            if(isset($res->code)){
                $requestResponse=[
                    'success' => false,
                    'message' => $res->description
                ];
            }else{
                $details = $res->details;
                $requestResponse = [
                    'success' => true,
                    'message' =>$details->status,
                    'pin' => $details->pins
                ];
            }

        }
        curl_close($ch);
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
            ServiceType::SPECTRANET
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }
}
