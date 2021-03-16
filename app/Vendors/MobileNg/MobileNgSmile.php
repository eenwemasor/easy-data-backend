<?php


namespace App\Vendors\MobileNg;


use App\Enums\ServiceType;
use App\Enums\SmileTransactionType;
use App\GraphQL\Errors\GraphqlError;
use App\User;

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
                    'message' =>$details->status
                ];
            }

        }
        curl_close($ch);
        return $requestResponse;
    }

    public function validate_smart_card($args)
    {
        $url = "https://mobilenig.com/API/bills/user_check?";
        $param = [
            "service" => $args['service'] === SmileTransactionType::BUNDLE ? "SMILE_DATA":"SMILE_RECHARGE",
            "number" => $args['number'],
        ];
        $request = $this->compose_request($param);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $requestResponse = [
                'status' => "Failed",
                'message' =>curl_error($ch)
            ];
        } else {
            $details = json_decode($response)->details;
            if(isset($details->firstName)){
                $requestResponse=[
                    'name' => $details->firstName ." ".  $details->lastName. " ". $details->middleName,
                    'status' =>"Success",
                    'type' => "SMILE",
                    'message' => "Successful"
                ];
            }else{
                $requestResponse = [
                    'status' => "Failed",
                    'message' =>$details->errorCode
                ];
            }

        }
        curl_close($ch);
        return $requestResponse;
    }

    private function get_url($transactionType)
    {
        if ($transactionType == SmileTransactionType::BUNDLE) {
            return "https://mobilenig.com/API/bills/smile_data?";
        } else {
            return 'https://mobilenig.com/API/bills/smile_recharge?';
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
            ServiceType::SMILE
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }
}
