<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class User
{
    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function get_users($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = \App\User::query();

        if (isset($args['status'])) {
           $query->where('active', $args['status']);
        }
        if (isset($args['search'])) {
            $search = $args['search'];
            $query->where('username', 'LIKE', '%' . $search . '%')
                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%')
                ->orWhere('phone', 'LIKE', '%' . $search . '%')
                ->orWhere('active', 'LIKE', '%' . $search . '%');
        }
        return $query;

    }
}
