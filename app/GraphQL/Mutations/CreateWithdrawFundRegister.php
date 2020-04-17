<?php

namespace App\GraphQL\Mutations;

use App\Services\WithdrawFundRequestRegisterService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateWithdrawFundRegister
{
    /**
     * @var WithdrawFundRequestRegisterService
     */
    private $fundRequestRegisterService;

    /**
     * CreateWithdrawFundRegister constructor.
     * @param WithdrawFundRequestRegisterService $fundRequestRegisterService
     */
    function __construct(WithdrawFundRequestRegisterService $fundRequestRegisterService)
    {
        $this->fundRequestRegisterService = $fundRequestRegisterService;
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
       return $this->fundRequestRegisterService->create($args);
    }

    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     */
    public function initiate_transfer_fund($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      return  $this->fundRequestRegisterService->initiate_transfer_fund($args['otp']);
    }
}
