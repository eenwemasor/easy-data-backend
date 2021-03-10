<?php

namespace App\GraphQL\Mutations;

use App\Services\CreateUserService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateUser
{
    /**
     * @var CreateUserService
     */
    private $create_user_service;

    /**
     * CreateUser constructor.
     * @param CreateUserService $create_user_service
     */
    function __construct(CreateUserService $create_user_service)
    {
        $this->create_user_service = $create_user_service;
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->create($args);

    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\User
     */
    public function activate_account($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->activate_account($args['user_id']);

    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\User
     */
    public function create_transaction_pin($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->create_transaction_pin($args['user_id']);

    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\User
     */
    public function update_transaction_pin($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->update_transaction_pin($args['user_id'], $args['current_transaction_pin'], $args['new_transaction_pin']);

    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\User
     */
    public function block_account($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->block_account($args['user_id']);

    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\User
     */
    public function un_block_account($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->un_block_account($args['user_id']);

    }


    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \App\User
     */
    public function delete_account($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->create_user_service->delete_account($args['id']);

    }

}
