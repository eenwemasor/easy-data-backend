<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\SearchBuilder;
use App\WithdrawalTransaction;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetAllWithdrawalTransactions
{
    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Builder
     */
    public function get_all_transactions($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $from = $args['from_date'];
        $to = $args['to_date'];
        $status = $args['status'];
        $search = $args['search'];
        return SearchBuilder::search_builder(WithdrawalTransaction::query(),'withdrawal_transactions', $from,$to,$status,$search);
    }
}
