<?php


namespace App\Http\Controllers;

use App\AdminChannelUtil;
use App\Enums\TransactionType;
use App\ReferralReward;
use App\Repositories\CreateUserRepository;
use App\Repositories\WalletTransactionRepository;
use App\Services\CreateUserService;
use App\Services\WalletTransactionService;
use App\User;
use Illuminate\Http\Request;

class MonnifyController
{
    /**
     * @param Request $request
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $data = $request;
        $referral_reward = ReferralReward::first();
        $admin_util = AdminChannelUtil::first();
        $hash_string = env("MONNIFY_SECRET_KEY") . "|" . $data->paymentReference . "|" . $data->amountPaid . "|" . $data->paidOn . "|" . $data->transactionReference;
        $transaction_service = new WalletTransactionService(new WalletTransactionRepository());
        $create_user_service = new CreateUserService(new CreateUserRepository(), $transaction_service);
        $registration_fee = ReferralReward::first()->registration_fee;
        $hash = hash("sha512", $hash_string);


        $amount =  $data->amountPaid - $admin_util->monnify_bank_service_charge;

        if ($hash === $data->transactionHash) {
            $reference = $data->product['reference'];
            $user = User::where('username', $reference)->first();

            $wallet_transaction_settings = [
                "transaction_type" => TransactionType::CREDIT,
                "description" => "Monnify bank deposit",
                "amount" => $amount,
                "beneficiary" => $user->full_name,
                "user_id" => $user->id
            ];
            $wallet_transaction = $transaction_service->create($wallet_transaction_settings);
            $transaction_service->referrer_wallet_deposit_bonus($user, $data->amountPaid);

            if ($data->amountPaid >= $referral_reward->wallet_funding_bonus_qualification_amount) {
                $wallet_transaction_settings = [
                    "beneficiary" => $user->full_name,
                    "user_id" => $user->id
                ];
                $transaction_service->bonus_above_funding_qualification_limit($user, $wallet_transaction_settings);
            }
        } else {
            throw new \Exception($request);
        }
    }
}
