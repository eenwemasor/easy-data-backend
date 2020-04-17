<?php


namespace App\Repositories;


use App\Contracts\ElectricityTransactionContract;
use App\ElectricityTransaction;
use App\Enums\TransactionStatus;
use App\Http\Controllers\SendSMSController;
use App\PowerPlanList;
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



        $sms = new SendSMSController();
        $plan_data = PowerPlanList::find($electricityTransaction["plan"]);

        $beneficiary_name = $electricityTransaction["beneficiary_name"];
        $meter_number = $electricityTransaction["meter_number"];
        $plan = $plan_data->description;
        $amount = $electricityTransaction["amount"];

        $message = "Electricity Purchase Request:  Beneficiary Name: "
            .$beneficiary_name." Meter Number: "
            .$meter_number. "  Plan: ".$plan. " Amount: ".$amount;

        $sms->sendSMS($message);
        $electricityTransaction['plan'] = $plan_data->description;
        return ElectricityTransaction::create($electricityTransaction);

    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_successful(string $transaction_id): ElectricityTransaction
    {

        $transaction = ElectricityTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;

    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_failed(string $transaction_id): ElectricityTransaction
    {
        $transaction = ElectricityTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}