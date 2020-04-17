<?php

namespace App\GraphQL\Mutations;

use App\Services\CableTransactionService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CableTransaction
{
    private $cable_transaction;
    /**
     * CableTransactionEvent constructor.
     * @param CableTransactionService $cable_transaction_service
     */
    public function __construct(CableTransactionService $cable_transaction_service)
    {
        $this->cable_transaction = $cable_transaction_service;
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
        return $this->cable_transaction->create($args);

    }

    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\CableTransaction
     */
    public function mark_transaction_successful($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->cable_transaction->mark_transaction_successful($args['transaction_id']);

    }

    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\CableTransaction
     */
    public function mark_transaction_failed($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->cable_transaction->mark_transaction_failed($args['transaction_id']);

    }
}
