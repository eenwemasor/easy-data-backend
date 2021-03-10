<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 01:00
 */

namespace App\Services;


use App\AccountLevel;
use App\Enums\AccountAccessibility;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\Events\TransactionPin;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendVerificationUrlController;
use App\ReferralReward;
use App\Repositories\CreateUserRepository;
use App\User;

class CreateUserService
{
    /**
     * @var CreateUserRepository
     */
    private $create_user_contract_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * CreateUserService constructor.
     * @param CreateUserRepository $create_user_contract_repository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(CreateUserRepository $create_user_contract_repository, WalletTransactionService $walletTransactionService)
    {
        $this->create_user_contract_repository = $create_user_contract_repository;
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

            if($referrer){
                $referrer_id = $referrer->id;
            }else{
                throw  new GraphqlError("Referrer Does not Exist");
            }
        }
        $monnifyData = json_decode($this->createMonnifyAccount($user))->responseBody;
        $user['unique_id'] =  $user['username']. uniqid();
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

        $user = $this->create_user_contract_repository->create($user);
        $verify = new SendVerificationUrlController();
        $verify->sendVerificationLink([
            'name'=>$user->full_name,
            'email'=>$user->email,
            'user_unique_id' =>$user->unique_id
        ]);

        return $user;
    }

    /**
     * @param string $user_id
     * @return \App\User
     */
    public function create_transaction_pin(string $user_id)
    {
        $user = $this->create_user_contract_repository->create_transaction_pin($user_id);

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
        $user = $this->create_user_contract_repository->update_transaction_pin($user_id, $current_transaction_pin, $new_transaction_pin);
        $message = "You Successfully Updated your Subpay Transaction Pin";
        event(new TransactionPin($user, $message));

        return $user;
    }

    /**
     * @param string $user_id
     * @throws
     */
    public function activate_account(string $user_id)
    {
        $referral_rewards = ReferralReward::first();
        $registration_fee = $referral_rewards->registration_fee;
        $direct_referrer_percentage = $referral_rewards->direct_referrer_percentage;
        $indirect_referrer_percentage = $referral_rewards->indirect_referrer_percentage;
        $referee_percentage = $referral_rewards->referee_percentage;
        $site_percentage = $referral_rewards->site_percentage;

        $direct_referrer = null;
        $indirect_referrer = null;

        $user = User::find($user_id);
        if (!$user->active) {
            $admin = User::where('accessibility', AccountAccessibility::ADMIN)->first();


            if ($user->referrer_id) {
                $amount_gained = $direct_referrer_percentage / 100 * $registration_fee;
                $direct_referrer = User::find($user->referrer_id);

                $directReferrerWalletTransactionData = ['transaction_type' => TransactionType::CREDIT, 'description' => "Referral Reward", 'amount' => $amount_gained, 'beneficiary' => "Self", 'user_id' => $direct_referrer->id,];

                $this->walletTransactionService->create($directReferrerWalletTransactionData, WalletType::BONUS_WALLET);
            }

            if ($direct_referrer) {
                if ($direct_referrer->referrer_id) {
                    $amount_gained = $indirect_referrer_percentage / 100 * $registration_fee;
                    $indirect_referrer = User::find($direct_referrer->referrer_id);
                    $indirectReferrerWalletTransactionData = ['transaction_type' => TransactionType::CREDIT, 'description' => "Referral Reward", 'amount' => $amount_gained, 'beneficiary' => "Self", 'user_id' => $indirect_referrer->id,];

                    $this->walletTransactionService->create($indirectReferrerWalletTransactionData, WalletType::BONUS_WALLET);
                }
            }

            $userWalletTransactionData = ['transaction_type' => TransactionType::CREDIT, 'description' => "Referral Reward", 'amount' => $referee_percentage / 100 * $registration_fee, 'beneficiary' => "Self", 'user_id' => $user->id,];

            $this->walletTransactionService->create($userWalletTransactionData, WalletType::BONUS_WALLET);

            $adminWalletTransactionData = ['transaction_type' => TransactionType::CREDIT, 'description' => "Referral Reward", 'amount' => $site_percentage / 100 * $registration_fee, 'beneficiary' => "Self", 'user_id' => $admin->id,];

            $this->walletTransactionService->create($adminWalletTransactionData, WalletType::BONUS_WALLET);
            $user->active = true;
            $user->save();
            return $user;
        } else {
            throw new GraphqlError("Account already activated");
        }

    }


    public function block_account( string $user_id)
    {
        $user = User::find($user_id);
        $user->accessibility = AccountAccessibility::BLOCKED;
        $user->save();

        return $user;
    }


    public function un_block_account( string $user_id)
    {
        $user = User::find($user_id);
        $user->accessibility = AccountAccessibility::USER;
        $user->save();

        return $user;
    }





    /**
     * @param string $user_id
     * @throws
     */
    public function reward_referrals(string $user_id, $amount)
    {
        $referral_rewards = ReferralReward::first();
        $direct = $referral_rewards->direct_referrer_percentage_wallet_funding;
        $indirect = $referral_rewards->indirect_referrer_percentage_wallet_funding;

        $direct_referrer = null;
        $indirect_referrer = null;

        $user = User::find($user_id);

        if ($user->referrer_id) {
            $amount_gained = $direct / 100 * $amount;
            $direct_referrer = User::find($user->referrer_id);

            $directReferrerWalletTransactionData = ['transaction_type' => TransactionType::CREDIT, 'description' => "Referral Reward", 'amount' => $amount_gained, 'beneficiary' =>  $direct_referrer->full_name, 'user_id' => $direct_referrer->id,];

            $this->walletTransactionService->create($directReferrerWalletTransactionData, WalletType::BONUS_WALLET);
        }

        if ($direct_referrer) {
            if ($direct_referrer->referrer_id) {
                $amount_gained = $indirect/ 100 * $amount;
                $indirect_referrer = User::find($direct_referrer->referrer_id);
                $indirectReferrerWalletTransactionData = ['transaction_type' => TransactionType::CREDIT, 'description' => "Referral Reward", 'amount' => $amount_gained, 'beneficiary' => $indirect_referrer->full_name, 'user_id' => $indirect_referrer->id,];

                $this->walletTransactionService->create($indirectReferrerWalletTransactionData, WalletType::BONUS_WALLET);
            }
        }

        $user->active = true;
        $user->save();
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

    static  public function totalUserWalletBalance($wallet_type)
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
}
