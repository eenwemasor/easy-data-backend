<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/03/2020
 * Time: 10:08
 */

namespace App\Repositories\APIRequests;

use App\GraphQL\Errors\GraphqlError;
use App\PowerPlanList;

class ValidateMobileNgTransactionRepository
{
    /**
     * @var ValidateTransactions
     */
    private $validateTransactions;

    /**
     * ValidateMobileNgTransactionRepository constructor.
     * @param ValidateTransactions $validateTransactions
     */
    function __construct(ValidateTransactions $validateTransactions)
    {
        $this->validateTransactions = $validateTransactions;
    }

    /**
     * @param array $data
     * @param $amount
     * @return array
     * @throws GraphqlError
     */
    public function get_cable_card_details(array $data,$amount): array
    {
        $api_wallet = $this->validateTransactions->get_api_account_info();
        if($api_wallet < $amount){
            throw new GraphqlError("Service is not available currently, please try again later");
        }

        $url = config('constant.MOBILE_NG_DECODER_USER_CHECK');
        $api_key = config('constant.MOBILE_NG_API_KEY');
        $username = config('constant.MOBILE_NG_API_USERNAME');
        $service = $data['service'];
        $number = $data['number'];

        $request = "";

        $param = [
            "username"=>$username,
            "api_key"=>$api_key,
            "service"=>$service,
            "number"=>$number
        ];

        foreach($param as $key=>$val) //traverse through each member of the param array
        {
            $request .= $key . "=" . urlencode($val); //we have to urlencode the values
            $request .= '&'; //append the ampersand (&) sign after each paramter/value pair
        }

        $len = strlen($request) - 1;
        $request = substr($request, 0, $len); //remove the final ampersand sign from the request

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        $response = curl_exec($ch);

        curl_close($ch);
        $json_data =json_decode($response);

        if(isset($json_data->details->errorMessage)){
            throw new GraphqlError($json_data->details->errorMessage);
        }else{
            if($service == "STARTIMES"){
                $details = $json_data->details;

                if($details->returnCode == "0"){
                    $accountDetails = [
                        'accountStatus'=>"OPEN",
                        'first_name'=>$details->customerName,
                        'last_name'=>$details->customerName,
                        'customer_type'=>$details->customerType,
                        'invoice_period'=>$details->billAmount,
                        'due_date'=>"NULL",
                        'customer_number'=>$details->customerNumber,
                    ];
                    return $accountDetails;
                }{
                    throw new GraphqlError("The provided decoder or smart number is currently not open");
                }

            }else{
                $details = $json_data->details;
                $accountDetails = [
                    'accountStatus'=>$details->accountStatus,
                    'first_name'=>$details->firstName,
                    'last_name'=>$details->lastName,
                    'customer_type'=>$details->customerType,
                    'invoice_period'=>$details->invoicePeriod,
                    'due_date'=>$details->dueDate,
                    'customer_number'=>$details->customerNumber,
                ];

                return $accountDetails;
            }

        }
    }

    public function get_bills_meter_details($data)
    {
        $api_wallet = $this->validateTransactions->get_api_account_info();
        $plan = PowerPlanList::find($data['plan']);
        $api_key = config('constant.MOBILE_NG_API_KEY');
        $username = config('constant.MOBILE_NG_API_USERNAME');

//        if ($api_wallet < $plan['amount']) {
//            throw new GraphqlError("Service is not available currently, please try again later");
//        }


        $url = config('constant.MOBILE_NG_DECODER_USER_CHECK');
        $disco = $data['disco'];
        $number = $data['number'];
        $type = $data['type'];


        $service = $disco."_".$type;
        //Input parameters as given in the documentation
        $request = "";

        $param = [
            "username"=>$username,
            "api_key"=>$api_key,
            "service"=>$service,
            "number"=>$number
        ];

        foreach($param as $key=>$val) //traverse through each member of the param array
        {
            $request .= $key . "=" . urlencode($val); //we have to urlencode the values
            $request .= '&'; //append the ampersand (&) sign after each paramter/value pair
        }

        $len = strlen($request) - 1;
        $request = substr($request, 0, $len); //remove the final ampersand sign from the request

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url$request");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        $response = curl_exec($ch);

        curl_close($ch);
        $json_data =json_decode($response);

        if(isset($json_data->details->errorMessage)){
            throw new GraphqlError($json_data->details->errorMessage);
        }else{
            $details = $json_data->details;
            var_dump($json_data);
            $accountDetails = [];
            if($service == "IKEJA" && isset($details->address) ) {
                $accountDetails['address'] = $details->address;
                $accountDetails['outstanding_amount'] = $details->outstandingAmount;
                $accountDetails['name'] = $details->name;
                $accountDetails['minimum_amount'] = $details->minimumAmount;
                $accountDetails['account_number'] = $details->accountNumber;
                $accountDetails['customer_dt_number'] = $details->customerDtNumber;

            }else if($service == "IKEJA_TOKEN" && isset($details->canVend) ) {

                if ($details->canVend = "true") {
                    $accountDetails['name'] = $details->name;
                    $accountDetails['address'] = $details->address;
                    $accountDetails['minimum_amount'] = $details->minimumAmount;
                    $accountDetails['can_vend'] = $details->canVend;
                    $accountDetails['meter_number'] = $details->meterNumber;
                    $accountDetails['customer_dt_number'] = $details->customerDtNumber;
                } else {
                    throw new GraphqlError("You Currently cannot Vend.");
                }
            }else if($service = "EKO_PREPAID" && isset($details->customerAddress)) {

                if ($details->status == "true") {
                    $accountDetails['address'] = $details->customerAddress;
                    $accountDetails['customer_district'] = $details->customerDistrict;
                    $accountDetails['name'] = $details->customerName;
                    $accountDetails['status'] = $details->status;
                } else {
                    throw new GraphqlError("You Currently cannot Vend.");
                }

            }else if($service = "EKO_POSTPAID" && isset($details->businessUnit)) {
                $accountDetails['account_number'] = $details->accountNumber;
                $accountDetails['meter_number'] = $details->meterNumber;
                $accountDetails['name'] = $details->customerName;
                $accountDetails['business_unit'] = $details->businessUnit;

            }else if($service = "KEDCO_PREPAID" && isset($details->businessUnit)) {
                $accountDetails['name'] = $details->customerName;
                $accountDetails['minimum_amount'] = $details->minimumPurchase;
                $accountDetails['business_unit'] = $details->businessUnit;
                $accountDetails['phone_number'] = $details->phoneNumber;
                $accountDetails['undertaking'] = $details->undertaking;
                $accountDetails['customer_arrears'] = $details->customerArrears;
                $accountDetails['customer_reference'] = $details->customerReference;
                $accountDetails['email'] = $details->email;
                $accountDetails['meter_number'] = $details->meterNumber;
            }
            else if($service = "KEDCO_POSTPAID" && isset($details->businessUnit)){
                $accountDetails['name'] = $details->customerName;
                $accountDetails['minimum_amount'] = $details->minimumPurchase;
                $accountDetails['business_unit'] = $details->businessUnit;
                $accountDetails['phone_number'] = $details->phoneNumber;
                $accountDetails['undertaking'] = $details->undertaking;
                $accountDetails['customer_arrears'] = $details->customerArrears;
                $accountDetails['customer_reference'] = $details->customerReference;
                $accountDetails['email'] = $details->email;
                $accountDetails['meter_number'] = $details->meterNumber;
            }
            else if($service = "ABUJA_PREPAID" && isset($details->uniqueCode)){
                $accountDetails['name'] = $details->customerName;
                $accountDetails['minimum_amount'] = $details->minimumVend;
                $accountDetails['unique_code'] = $details->uniqueCode;
            }
            else if($service = "ABUJA_POSTPAID" && isset($details->uniqueCode)){
                $accountDetails['name'] = $details->customerName;
                $accountDetails['minimum_amount'] = $details->minimumVend;
                $accountDetails['unique_code'] = $details->uniqueCode;
            }else{
                throw new GraphqlError("The provided Meter or Smart Number is currently not open");
            }

            return $accountDetails;
        }

    }
}