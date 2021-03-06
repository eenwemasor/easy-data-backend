<?php


namespace App\Vendors\MobileNg;


use App\Enums\ResultCheckerExamBody;
use App\GraphQL\Errors\GraphqlError;

class MobileNgSpectranet extends MobileNgRoot
{
    /**
     * @param $spectranetPackage
     * @param $reference
     * @return mixed
     * @throws GraphqlError
     */
    public function purchase_spectranet($spectranetPackage, $reference, $args)
    {
        $this->get_account_info($spectranetPackage->vendor_price);
        $url ="https://mobilenig.com/API/bills/spectranet";
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

        $response = curl_exec($ch);
        error_log($response);
        curl_close($ch);
        return json_decode($response);
    }
}
