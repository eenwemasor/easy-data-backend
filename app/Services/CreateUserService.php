<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 01:00
 */

namespace App\Services;


use App\AccountLevel;
use App\AdminChannelUtil;
use App\Enums\AccountAccessibility;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletType;
use App\Events\TransactionPin;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendVerificationUrlController;
use App\ReferralReward;
use App\Repositories\CreateUserRepository;
use App\User;
use App\WalletTransaction;

class CreateUserService
{
    /**
     * @var CreateUserRepository
     */
    private $createUserRepository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * CreateUserService constructor.
     * @param CreateUserRepository $createUserRepository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(CreateUserRepository $createUserRepository, WalletTransactionService $walletTransactionService)
    {
        $this->createUserRepository = $createUserRepository;
        $this->walletTransactionService = $walletTransactionService;
    }

    static public function total_transaction_statistics($from, $to)
    {
        $total_users = User::where('accessibility', AccountAccessibility::USER)->count();
        $total_active_users = User::where('accessibility', AccountAccessibility::USER)->where('active', true)->count();
        $total_inactive_users = User::where('accessibility', AccountAccessibility::USER)->where('active', false)->count();
        return [
            'total_users' => $total_users,
            'total_active_users' => $total_active_users,
            'total_inactive_users' => $total_inactive_users,
            'total_user_wallet_balance' => self::totalUserWalletBalance('wallet'),
            'total_user_bonus_wallet_balance' => self::totalUserWalletBalance('bonus_wallet')
        ];
    }

    public function create(array $user)
    {
        $referrer_id = null;
        if (isset($user['referrer_id'])) {
            $referrer = User::where('unique_id', $user['referrer_id'])->first();

            if ($referrer) {
                $referrer_id = $referrer->id;
            } else {
                throw  new GraphqlError("Referrer Does not Exist");
            }
        }
        $monnifyData = json_decode($this->createMonnifyAccount($user))->responseBody;
        $user['unique_id'] = $user['username'] . uniqid();
        $user['accessibility'] = AccountAccessibility::USER;
        $user['wallet'] = 0;
        $user['email_confirmed'] = false;
        $user['phone_verified'] = false;
        $user['active'] = false;
        $user['account_level_id'] = AccountLevel::where('name', 'Free')->first()->id;
        $user['bonus_wallet'] = 0;
        $user['referrer_id'] = $referrer_id;
        $user['monnify_account_number'] = $monnifyData->accountNumber;
        $user['monnify_bank_name'] = $monnifyData->bankName;
        $user['monnify_bank_code'] = $monnifyData->bankCode;
        $user['monnify_collection_channel'] = $monnifyData->collectionChannel;
        $user['monnify_reservation_channel'] = $monnifyData->reservationReference;

        $user = $this->createUserRepository->create($user);
        $verify = new SendVerificationUrlController();
        $verify->sendVerificationLink([
            'name' => $user->full_name,
            'email' => $user->email,
            'user_unique_id' => $user->unique_id
        ]);

        return $user;
    }

    /**
     * @param string $user_id
     * @return \App\User
     */
    public function create_transaction_pin(string $user_id)
    {
        $user = $this->createUserRepository->create_transaction_pin($user_id);

        $message = "You Successfully Created your Subpay Transaction Pin";

        event(new TransactionPin($user, $message));

        return $user;
    }

    /**
     * @param string $user_id
     * @param string $current_transaction_pin
     * @param string $new_transaction_pin
     * @return User
     */
    public function update_transaction_pin(string $user_id, string $current_transaction_pin, string $new_transaction_pin)
    {
        $user = $this->createUserRepository->update_transaction_pin($user_id, $current_transaction_pin, $new_transaction_pin);
        $message = "You Successfully Updated your Subpay Transaction Pin";
        event(new TransactionPin($user, $message));

        return $user;
    }

    /**
     * @param array $args
     * @throws GraphqlError
     */
    public function upgrade_account(array $args)
    {
        $user = User::find($args['user_id']);
        $accountLevel = AccountLevel::find($args['account_level_id']);

        $directReferrer = null;
        $indirectReferrer = null;

        if (isset($user->referrer_id)) {
            $directReferrer = User::find(($user->referrer_id));
            $amount = ($accountLevel->cost_to_upgrade / 100) * $directReferrer->account_level->direct_referrer_percentage_bonus;
            $this->creditUserBonus($user->full_name . "'s account upgrade bonus", $directReferrer, $amount);
        }

        if (isset($directReferrer) && isset($directReferrer->referrer_id)) {
            $indirectReferrer = User::find(($directReferrer->referrer_id));
            $amount = ($accountLevel->cost_to_upgrade / 100) * $indirectReferrer->account_level->indirect_referrer_percentage_bonus;
            $this->creditUserBonus($directReferrer->full_name . " referral's Account upgrade bonus [Down line]", $indirectReferrer, $amount);
        }

        $user->account_level_id = $accountLevel->id;
        $user->save();
        return $user;
    }


    public function block_account(string $user_id)
    {
        $user = User::find($user_id);
        $user->accessibility = AccountAccessibility::BLOCKED;
        $user->save();

        return $user;
    }


    public function un_block_account(string $user_id)
    {
        $user = User::find($user_id);
        $user->accessibility = AccountAccessibility::USER;
        $user->save();

        return $user;
    }


    /**
     * @param string $user_id
     * @param $amount
     * @return
     * @throws GraphqlError
     */
    public function reward_referrals(string $user_id, $amount)
    {
        $user = User::find($user_id);

        $directReferrer = null;
        $indirectReferrer = null;

        if (isset($user->referrer_id)) {
            $directReferrer = User::find(($user->referrer_id));
            $amount = ($amount / 100) * $directReferrer->account_level->wallet_deposit_direct_referrer_percentage_bonus;
            $this->creditUserBonus($user->full_name . "'s wallet deposit bonus", $directReferrer, $amount);
        }

        if (isset($directReferrer) && isset($directReferrer->referrer_id)) {
            $indirectReferrer = User::find(($directReferrer->referrer_id));
            $amount = ($amount / 100) * $indirectReferrer->account_level->wallet_deposit_indirect_referrer_percentage_bonus;
            $this->creditUserBonus($directReferrer->full_name . " referral's wallet deposit bonus [Down line]", $indirectReferrer, $amount);
        };
        return $user;
    }


    /**
     * @param array $user
     */
    protected function createMonnifyAccount(array $user)
    {
        $monnify_data = null;
        try {
            $monnify = new MonnifyService();
            $monnify_data = $monnify->reserveAnAccount($user);
        } catch (\Exception $e) {

        }

        return $monnify_data;
    }


    /**
     * @param User $user
     * @return bool|string|null
     */
    protected function deleteMonnifyAccount(User $user)
    {
        $monnify_data = null;
        try {
            $monnify = new MonnifyService();
            $monnify_data = $monnify->deleteReservedAccount($user);
        } catch (\Exception $e) {
        }

        return $monnify_data;
    }

    static public function totalUserWalletBalance($wallet_type)
    {
        return User::all()->sum($wallet_type);
    }


    /**
     * @param string $user_id
     * @return mixed
     */
    public function delete_account(string $id)
    {
        $user = User::find($id);
        $this->deleteMonnifyAccount($user);
        $user->delete();
        return $user;
    }

    /**
     * @param $description
     * @param $user
     * @param $amount
     * @return array
     * @throws GraphqlError
     */
    private function creditUserBonus($description, $user, $amount): array
    {
        $walletTransactionData = [
            'transaction_type' => TransactionType::CREDIT,
            'description' => $description,
            'beneficiary' => $user->full_name,
            'user_id' => $user->id,
            'amount' => $amount
        ];
        $this->walletTransactionService->create($walletTransactionData, WalletType::BONUS_WALLET);
        return $walletTransactionData;
    }


    /**
     * @param $requestData
     * @throws GraphqlError
     * @throws \Exception
     */
    public function handle_monnify_deposit($requestData)
    {
        $data = $requestData;
        $admin_util = AdminChannelUtil::first();
        $hash_string = env("MONNIFY_SECRET_KEY") . "|" . $data->paymentReference . "|" . $data->amountPaid . "|" . $data->paidOn . "|" . $data->transactionReference;
        $hash = hash("sha512", $hash_string);
        $amount =  $data->amountPaid - $admin_util->monnify_bank_service_charge;

        $transactionExists = WalletTransaction::where('reference', $data->transactionReference)->first();
        if(isset($transactionExists) && $transactionExists->status === WalletTransactionStatus::SUCCESSFUL){
            throw new \Exception("Duplicate transaction");
        }else {
            if ($hash === $data->transactionHash) {
                $reference = $data->product['reference'];
                $user = User::where('username', $reference)->first();

                $wallet_transaction_settings = [
                    "reference" => $data->transactionReference,
                    "transaction_type" => TransactionType::CREDIT,
                    "description" => "Monnify bank deposit",
                    "amount" => $amount,
                    "beneficiary" => $user->full_name,
                    "user_id" => $user->id

                ];
                $this->walletTransactionService->create($wallet_transaction_settings);
                $this->reward_referrals($user->id, $data->amountPaid);

            } else {
                throw new \Exception($requestData);
            }
        }
    }
}
