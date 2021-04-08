<?php


namespace App\Gateways;


use App\BankAccount;
use App\Enums\ServiceType;
use App\GraphQL\Errors\GraphqlError;
use App\User;
use App\Vendors\ApplyAccountLevelApplicables;

class Paystack extends ApplyAccountLevelApplicables
{
    private $url;
    private $header;

    public function __construct()
    {
        $this->url = env('PAYSTACK_URL');
        $this->header = array(
            "Authorization: Bearer ".env('PAYSTACK_SECRET_KEY'),
            "Cache-Control: no-cache",
        );

    }

    public function curl( $endpoint)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url. $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $this->header
        ));

        return $curl;
    }

    public function get_bank_list()
    {
        $curl  = $this->curl('bank');
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "GET");

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
           return json_decode($response)->data;
        }
    }


    /**
     * @param array $args
     * @return mixed
     * @throws GraphqlError
     */
    public function verify_bank_account(array $args)
    {
        $curl  = $this->curl("bank/resolve?account_number=".$args['bank_number']."&bank_code=".$args['bank_code']);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "GET");


        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new GraphqlError("cURL Error #:" . $err);
        } else {
            $res = json_decode($response);
            if($res->status){
                return $res->data;
            }else{
                throw new GraphqlError($res->message);
            }
        }
    }

    /**
     * @param BankAccount $bankAccount
     * @return BankAccount
     * @throws GraphqlError
     */
    public function create_transfer_recipient(BankAccount $bankAccount): BankAccount
    {
        $curl  = $this->curl("transferrecipient");


        $fields = [
            'type' => $bankAccount->bank->tyoe,
            'name' => $bankAccount->name,
            'account_number' => $bankAccount->bank_number,
            'bank_code' => $bankAccount->bank->code,
            'currency' => $bankAccount->bank->currency
        ];

        $fields_string = http_build_query($fields);

        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl,CURLOPT_POST, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new GraphqlError("cURL Error #: " . $err);
        } else {
            $res = json_decode($response);
            if($res->status === true){
                $data = $res->data;
                return $bankAccount::updateOrCreate(['recipient_code'=>$data->recipient_code]);
            }else{
                throw new GraphqlError($res->message);
            }
        }

    }


    /**
     * @param BankAccount $bankAccount
     * @param float $amount
     * @return mixed
     * @throws GraphqlError
     */
    public function initiate_transfer(BankAccount $bankAccount, float $amount)
    {
        $curl  = $this->curl("transfer");


        $fields = [
            'source' => "balance",
            'amount' => $amount,
            'recipient' => $bankAccount->recipient_code,
            'reason' => "Subpay withdrawal service"
        ];

        $fields_string = http_build_query($fields);

        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl,CURLOPT_POST, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw  new GraphqlError("cURL Error #:" . $err);
        } else {
            $res = json_decode($response);
            if($res->status === true){
                return $res->data;
            }else{
                throw new GraphqlError($res->message);
            }
        }
    }

    /**
     * @param $data
     * @param $amount
     * @return mixed
     */
    public function apply_discount($data, $amount)
    {
        $user = User::find($data['user_id']);
        $applicables = $user->account_level->applicables()->where('service_type',
            ServiceType::WITHDRAWAL
        )->get();
        return $this->apply_applicable($amount, $applicables);
    }

}
