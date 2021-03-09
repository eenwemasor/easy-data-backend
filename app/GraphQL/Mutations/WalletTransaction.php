<?php

namespace App\GraphQL\Mutations;

use App\Enums\TransactionType;
use App\ReferralReward;
use App\Services\CreateUserService;
use App\Services\WalletTransactionService;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class WalletTransaction
{
    /**
     * @var WalletTransactionService
     */
    private $wallet_transaction;
    /**
     * @var CreateUserService
     */
    private $createUserService;

    /**
     * WalletTransaction constructor.
     * @param WalletTransactionService $wallet_transaction_service
     * @param CreateUserService $createUserService
     */
    public function __construct(WalletTransactionService $wallet_transaction_service, CreateUserService $createUserService)
    {
        $this->wallet_transaction = $wallet_transaction_service;
        $this->createUserService = $createUserService;
    }
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = User::find($args['user_id']);
        $registration_fee = ReferralReward::first()->registration_fee;
        $wallet_transaction = $this->wallet_transaction->create($args);
        if($user->active){
            $this->createUserService->reward_referrals($args['user_id'], $args['amount']);
            return $wallet_transaction;
        }else{
            $args['amount']  = $registration_fee;
            $args['transaction_type']  = TransactionType::DEBIT;
            $args['description'] = "Account activation charge";
            $account_activation_charge = $this->wallet_transaction->create($args);
            $this->createUserService->activate_account($args['user_id']);
            return $account_activation_charge;
        }

    }


    public function withdraw_bonus_to_wallet($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->wallet_transaction->withdraw_bonus_to_wallet($args['user_id']);
    }
}
