<?php

namespace App\GraphQL\Mutations;

use App\Services\AirtimeToCashTransactionService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AirtimeToCashTransaction
{
    /**
     * @var AirtimeToCashTransactionService
     */
    private $airtime_to_cash_transaction_service;

    /**
     * AirtimeToCashTransaction constructor.
     * @param AirtimeToCashTransactionService $airtime_to_cash_transaction_service
     */
    function __construct(AirtimeToCashTransactionService $airtime_to_cash_transaction_service)
    {
        $this->airtime_to_cash_transaction_service = $airtime_to_cash_transaction_service;
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
        // TODO implement the resolver

        return $this->airtime_to_cash_transaction_service->create($args);
    }
}
