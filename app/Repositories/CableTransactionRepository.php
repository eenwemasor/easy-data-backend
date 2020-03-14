<?php


namespace App\Repositories;


use App\CableTransaction;
use App\Contracts\CableTransactionContract;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\Wallet;
use App\User;

class CableTransactionRepository implements CableTransactionContract
{

    /**
     * @param array $cableTransaction
     * @param User $user
     * @return CableTransaction
     */
    public function create(array $cableTransaction): CableTransaction
    {
        // TODO: Implement create() method.
        $sms = new SendSMSController();
        $decoder = $cableTransaction["decoder"];
        $beneficiary_name = $cableTransaction["beneficiary_name"];
        $decoder_number = $cableTransaction["decoder_number"];
        $plan = $cableTransaction["plan"];
        $amount = $cableTransaction["amount"];

        $message = "Cable Tv Purchase Request: Decoder: "
            .$decoder." Beneficiary Name: "
            .$beneficiary_name." Decoder Number: "
            .$decoder_number. "  Plan: ".$plan. " Amount: ".$amount;

        $sms->sendSMS($message);

        return CableTransaction::create($cableTransaction);
    }
}