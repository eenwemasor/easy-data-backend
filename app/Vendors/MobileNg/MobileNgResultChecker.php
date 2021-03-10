<?php


namespace App\Vendors\MobileNg;


use App\Enums\ResultCheckerExamBody;
use App\GraphQL\Errors\GraphqlError;

class MobileNgResultChecker extends MobileNgRoot
{
    /**
     * @param $result_checker_packer
     * @param $reference
     * @return mixed
     * @throws GraphqlError
     */
    public function purchase_result_checker($result_checker_packer, $reference)
    {
        $this->check_wallet_api($result_checker_packer->vendor_price);
        $url = $this->get_url($result_checker_packer->examination_body);
        $param = [
            "product_code" => $result_checker_packer->product_code,
            "price" => $result_checker_packer->vendor_price,
            "trans_id" => $reference,
        ];
        $request = $this->compose_request($param);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable

        $response = curl_exec($ch);

        curl_close($ch);
        return json_decode($response);
    }

    private function get_url($examination_body)
    {
        if ($examination_body == ResultCheckerExamBody::WAEC) {
            return "https://mobilenig.com/API/bills/waec_test?";
        } else {
            return 'https://mobilenig.com/API/bills/neco_test?';
        }
    }
}
