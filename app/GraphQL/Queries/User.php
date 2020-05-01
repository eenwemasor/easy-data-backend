<?php

namespace App\GraphQL\Queries;

use App\Enums\AccountAccessibility;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;

class User
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function get_users($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $search = null;
        $user = null;

        if(isset($args['search'])){
            $search = $args['search'];
        }else{
            return  DB::table('users');
        }



        if($search === "true"){
            return DB::table('users')->where('active',true);
        }
        if ($search === "false"){
            return DB::table('users')->where('active',false);
        }

        return DB::table('users')->where('username','LIKE','%'.$search.'%')
            ->orWhere('full_name','LIKE','%'.$search.'%')
            ->orWhere('email','LIKE','%'.$search.'%')
            ->orWhere('phone','LIKE','%'.$search.'%')
            ->orWhere('active','LIKE','%'.$search.'%');

    }
}
