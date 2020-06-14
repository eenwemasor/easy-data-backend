<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/04/2020
 * Time: 09:06
 */

namespace App\Services;


use App\AdminChannelUtil;
use App\Enums\BulkSMSStatus;
use App\Enums\TransactionType;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendSMSController;
use App\Repositories\BulkSMSRepository;
use App\User;

class BulkSMSService
{
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;
    /**
     * @var BulkSMSRepository
     */
    private $bulkSMSRepository;

    /**
     * BulkSMSService constructor.
     * @param WalletTransactionService $walletTransactionService
     * @param BulkSMSRepository $bulkSMSRepository
     */
    function __construct(WalletTransactionService $walletTransactionService, BulkSMSRepository $bulkSMSRepository)
    {
        $this->walletTransactionService = $walletTransactionService;
        $this->bulkSMSRepository = $bulkSMSRepository;
    }


    /**
     * @param array $smsData
     * @return \App\BulkSMS
     * @throws GraphqlError
     */
    public function create(array $smsData)
    {
        $user = User::find($smsData["user_id"]);
        $charge_per_sms = AdminChannelUtil::all()->first()->sms_unit_charge;
        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $receivers_list = explode(",",$smsData['receivers']);
        $amount = count($receivers_list) * $charge_per_sms;

        $sms = new SendSMSController();


        $walletTransactionData = [
            'transaction_type'=>TransactionType::DEBIT,
            'description'=>"Bulk SMS Transaction",
            'user_id' => $smsData["user_id"],
            'beneficiary' => count($receivers_list)." Phone Numbers",
            'amount'=>$amount
            ];


        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);

        $smsData['amount'] = $amount;
        $smsData['reference'] = $walletTransactionResult['reference'];
        $smsData['status'] = BulkSMSStatus::DELIVERED;

        $sms->sendBulkSMS($smsData["message"], $smsData["receivers"]);

        return $this->bulkSMSRepository->create($smsData);


    }


    /**
     * @param string $transaction_id
     * @return mixed
     */
    public function mark_transaction_successful(string $transaction_id)
    {
        return $this->bulkSMSRepository->mark_transaction_successful($transaction_id);
    }


    /**
     * @param string $transaction_id
     * @return mixed
     */
    public function mark_transaction_failed(string $transaction_id)
    {

        return $this->bulkSMSRepository->mark_transaction_failed($transaction_id);
    }

}