<?php


namespace App\Vendors\MobileNg;


use App\Enums\ResultCheckerExamBody;
use App\GraphQL\Errors\GraphqlError;

class MobileNgResultChecker
{

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $api_key;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $username;

    /**
     * MobileNgTransaction constructor.
     */
    function __construct()
    {
        $this->api_key = config('constant.MOBILE_NG_API_KEY');
        $this->username = config('constant.MOBILE_NG_API_USERNAME');
    }

    /**
     * @param array $data
     * @param $amount
     * @return array
     * @throws GraphqlError
     */
    public function get_cable_card_details(array $data, $amount): array
    {
        $url = config('constant.MOBILE_NG_DECODER_USER_CHECK');
        $service = $data['service'];
        $number = $data['number'];
        $param = [
            "service" => $service,
            "number" => $number
        ];
        $request = $this->compose_request($param);//remove the final ampersand sign from the request

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable

        $response = curl_exec($ch);

        curl_close($ch);
        $json_data = json_decode($response);

        if (isset($json_data->message)) {
            throw new GraphqlError($json_data->message);
        } else {
            if ($service == "STARTIMES") {
                $details = $json_data->details;

                return [
                    'accountStatus' => "OPEN",
                    'first_name' => $details->customerName,
                    'last_name' => $details->customerName,
                    'customer_type' => $details->customerType,
                    'invoice_period' => $details->billAmount,
                    'due_date' => "NULL",
                    'customer_number' => $details->customerNumber,
                ];

            } else {
                $details = $json_data->details;
                return [
                    'accountStatus' => $details->accountStatus,
                    'first_name' => $details->firstName,
                    'last_name' => $details->lastName,
                    'customer_type' => $details->customerType,
                    'invoice_period' => $details->invoicePeriod,
                    'due_date' => $details->dueDate,
                    'customer_number' => $details->customerNumber,
                ];
            }

        }
    }

    /**
     * @param $result_checker_packer
     * @param $reference
     * @return mixed
     * @throws GraphqlError
     */
    public function purchase_result_checker($result_checker_packer, $reference)
    {
        $this->get_account_info($result_checker_packer->vendor_price);
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
            return "https://mobilenig.com/API/bills/waec?";
        } else {
            return 'https://mobilenig.com/API/bills/neco?';
        }
    }

    private function compose_request($param)
    {
        $request = "";
        foreach (array_merge(["username" => $this->username, "api_key" => $this->api_key], $param) as $key => $val) //traverse through each member of the param array
        {
            $request .= $key . "=" . urlencode($val); //we have to urlencode the values
            $request .= '&'; //append the ampersand (&) sign after each paramter/value pair
        }
        $len = strlen($request) - 1;
        return substr($request, 0, $len); //remove the final ampersand sign from the request
    }

    private function get_account_info($amount)
    {
        $url = 'https://mobilenig.com/API/balance?';
        $request = $this->compose_request([]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
        $response = curl_exec($ch);
        curl_close($ch);
        if (json_decode($response)->balance < $amount) throw new GraphqlError("Service is not available currently, please try again later");

    }

}
