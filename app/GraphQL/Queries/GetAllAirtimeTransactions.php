<?php

namespace App\GraphQL\Queries;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetAllAirtimeTransactions
{
    public function get_all_transactions($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $from = $args['from_date'];
        $to = $args['to_date'];
        $status = $args['status'];
        $transactions = null;
        if (isset($from) && isset($to) && !isset($status)) {
            $transactions = DB::table('airtime_transactions')
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
        } elseif (isset($from) && isset($to) && isset($status)) {
            $transactions = DB::table('airtime_transactions')
                ->where('status', $status)
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
        } elseif (isset($status)) {
            $transactions = DB::table('airtime_transactions')->where('status', $status);
        } else {
            $transactions =   DB::table('airtime_transactions');
        }
        return $transactions;
    }
}
