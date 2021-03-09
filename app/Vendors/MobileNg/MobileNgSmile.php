<?php


namespace App\Vendors\MobileNg;


use App\Enums\SmileTransactionType;
use App\GraphQL\Errors\GraphqlError;

class MobileNgSmile extends MobileNgRoot
{
    /**
     * @param $smilePlan
     * @param $reference
     * @return mixed
     * @throws GraphqlError
     */
    public function purchase_smile($smilePlan, $reference, $args)
    {

        $this->check_wallet_api(isset($smilePlan->vendor_price) ?$smilePlan->vendor_price: $args['amount']);
        $url = $this->get_url($args['transaction_type']);
        $param = [
            "smartno" => $args['smart_card_number'],
            "trans_id" => $reference,
        ];
        if($args['transaction_type'] == SmileTransactionType::BUNDLE){
            $param["product_code"] = $smilePlan->product_code;
            $param["price"] = $smilePlan->vendor_price;
        }else{
            $param["amount"] = $args["amount"];
        }
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

    private function get_url($transactionType)
    {
        if ($transactionType == SmileTransactionType::BUNDLE) {
            return "https://mobilenig.com/API/bills/smile_data?";
        } else {
            return 'https://mobilenig.com/API/bills/smile_recharge?';
        }
    }
}
