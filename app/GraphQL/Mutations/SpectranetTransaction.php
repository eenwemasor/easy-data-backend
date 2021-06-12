<?php

namespace App\GraphQL\Mutations;

use App\Services\SpectranetTransactionService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SpectranetTransaction
{
    private $spectranetTransactionService;

    /**
     * CableTransactionEvent constructor.
     * @param SpectranetTransactionService $spectranetTransactionService
     */
    public function __construct(SpectranetTransactionService $spectranetTransactionService)
    {
        $this->spectranetTransactionService = $spectranetTransactionService;
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
        return $this->spectranetTransactionService->create($args);

    }
}
