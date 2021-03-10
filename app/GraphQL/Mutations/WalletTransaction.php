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
    private $walletTransactionService;
    /**
     * @var CreateUserService
     */
    private $createUserService;

    /**
     * WalletTransaction constructor.
     * @param WalletTransactionService $walletTransactionService_service
     * @param CreateUserService $createUserService
     */
    public function __construct(WalletTransactionService $walletTransactionService_service, CreateUserService $createUserService)
    {
        $this->walletTransactionService = $walletTransactionService_service;
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
        $walletTransactionResult = $this->walletTransactionService->create($args);
        $this->createUserService->reward_referrals($args['user_id'], $args['amount']);
    }


    public function withdraw_bonus_to_wallet($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->walletTransactionService->withdraw_bonus_to_wallet($args['user_id']);
    }
}
