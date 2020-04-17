<?php

namespace App\GraphQL\Mutations;

use App\Services\UserBankService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserBank
{
    /**
     * @var UserBankService
     */
    private $user_bank_service;

    /**
     * UserBank constructor.
     * @param UserBankService $user_bank_service
     */
    function __construct(UserBankService $user_bank_service)
    {
        $this->user_bank_service = $user_bank_service;
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

       return  $this->user_bank_service->create($args);
    }
}
