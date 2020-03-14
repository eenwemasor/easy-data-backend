<?php


namespace App\Repositories;


use App\Contracts\ElectricityTransactionContract;
use App\ElectricityTransaction;
use App\Http\Controllers\SendSMSController;
use App\User;

class ElectricityTransactionRepository implements ElectricityTransactionContract
{

    /**
     * @param array $electricityTransaction
     * @param User $user
     * @return ElectricityTransaction
     */
    public function create(array $electricityTransaction): ElectricityTransaction
    {
        // TODO: Implement create() method.

        $sms = new SendSMSController();
        $decoder = $electricityTransaction["decoder"];
        $beneficiary_name = $electricityTransaction["beneficiary_name"];
        $decoder_number = $electricityTransaction["decoder_number"];
        $plan = $electricityTransaction["plan"];
        $amount = $electricityTransaction["amount"];

        $message = "Electricity Purchase Request: Decoder: "
            .$decoder." Beneficiary Name: "
            .$beneficiary_name." Decoder Number: "
            .$decoder_number. "  Plan: ".$plan. " Amount: ".$amount;

        $sms->sendSMS($message);
        return ElectricityTransaction::create($electricityTransaction);

    }
}