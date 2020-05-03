<?php

namespace App\GraphQL\Queries;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetAllBulkSMSTransactions
{
    public function get_all_transactions($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $from = $args['from_date'];
        $to = $args['to_date'];
        $status = $args['status'];
        $transactions = null;
        if (isset($from) && isset($to) && !isset($status)) {
            $transactions = DB::table('bulk_s_m_s_s')
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
        } elseif (isset($from) && isset($to) && isset($status)) {
            $transactions = DB::table('bulk_s_m_s_s')
                ->where('status', $status)
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
        } elseif (isset($status)) {
            $transactions = DB::table('bulk_s_m_s_s')->where('status', $status);
        } else {
            $transactions =   DB::table('bulk_s_m_s_s');
        }
        return $transactions;
    }
}
