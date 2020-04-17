<?php

namespace App\GraphQL\Mutations;

use App\Services\TransferFundTransactionService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class TransferFundTransaction
{
    /**
     * @var TransferFundTransactionService
     */
    private $transfer_fund_transaction_service;

    /**
     * TransferFundTransaction constructor.
     * @param TransferFundTransactionService $transfer_fund_transaction_service
     */
    function __construct(TransferFundTransactionService $transfer_fund_transaction_service)
    {
        $this->transfer_fund_transaction_service = $transfer_fund_transaction_service;
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
        return $this->transfer_fund_transaction_service->create($args);
    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\TransferFundTransaction
     */
    public function approve_transaction($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        return $this->transfer_fund_transaction_service->approve_transaction($args['transaction_id']);
    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\TransferFundTransaction
     */
    public function decline_transaction($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        return $this->transfer_fund_transaction_service->decline_transaction($args['transaction_id']);
}
}
