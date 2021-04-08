<?php

namespace App\GraphQL\Queries;

use App\CableTransaction;
use App\Http\Controllers\SearchBuilder;
use Illuminate\Database\Eloquent\Builder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetAllCableTransactions
{
    public function get_all_transactions($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $from = $args['from_date'];
        $to = $args['to_date'];
        $status = $args['status'];
        $search = $args['search'];
        return SearchBuilder::search_builder(CableTransaction::query(),'cable_transactions', $from,$to,$status,$search);
    }
}
