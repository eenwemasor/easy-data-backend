<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\SearchBuilder;
use App\WalletTransaction;
use Illuminate\Database\Eloquent\Builder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetAllWalletTransactions
{
    public function get_all_transactions($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $from = $args['from_date'];
        $to = $args['to_date'];
        $status = $args['status'];
        $search = $args['search'];
        return SearchBuilder::search_builder(WalletTransaction::query(),'wallet_transactions', $from,$to,$status,$search);
    }
}
