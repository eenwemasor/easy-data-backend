<?php


namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SearchBuilder
{
    /**
     * @param Builder $query
     * @param $table
     * @param $from_date
     * @param $to_date
     * @param $status
     * @param $search
     * @return Builder
     */
    static public function search_builder(Builder $query, $table, $from_date, $to_date, $status, $search): Builder
    {
        $from = $from_date ?: Carbon::createFromTimestamp(0)->toDateString();
        $to = $to_date ?: Carbon::now();


        $query->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
        $query->where(function ($query) use ($status) {
            if ($status) {
                $query->where('status', "=", $status);
            }
        });
        $columns = [];


        switch ($table) {
            case 'airtime_to_wallet_transactions':
            {
                $columns = ['network', 'amount', 'sender_phone', 'recipient_phone'];
                break;
            }
            case 'airtime_transactions':
            {
                $columns = ['network', 'amount', 'phone', 'method'];
                break;
            }
            case 'bulk_messages':
            {
                $columns = ['receivers', 'message'];
                break;
            }
            case 'cable_transactions':
            {
                $columns = ['decoder', 'decoder_number', 'beneficiary_name', 'amount', 'method'];
                break;
            }
            case 'data_transactions':
            {
                $columns = ['network', 'beneficiary', 'amount', 'method'];
                break;
            }
            case 'electricity_transactions':
            {
                $columns = ['meter_number', 'beneficiary_name', 'type', 'amount', 'method', 'token'];
                break;
            }
            case 'transfer_fund_transactions':
            {
                $columns = array_merge($columns, ['description', 'amount']);
                break;
            }
            case 'wallet_transactions':
            {
                $columns = ['transaction_type', 'description', 'amount', 'beneficiary'];
                break;
            }
            case 'quick_buys':
            {
                $columns = ['transaction_type', 'network', 'phone', 'decoder', 'decoder_number', 'amount', 'beneficiary', 'electricity_type', 'email','pins','examination_body'];
                break;
            }
            case 'airtime_print_transactions':
            {
                $columns = ['amount', 'quantity', ];
                break;
            }
            case 'withdrawal_transactions':
            case 'result_check_transactions':
            {
                $columns = ['amount'];
                break;
            }
            default:
            {
                break;
            }
        }
        $query->where('reference', 'LIKE', '%' . $search . '%');
        if ($search) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
            $query->orWhereHas('user', function (Builder $query) use ($search){
                $query->where('full_name', 'like', '%' . $search . '%');
                $query->orWhere('username', 'like', '%' . $search . '%');
            });
        }
        return $query;

    }
}
