<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14/04/2020
 * Time: 06:34
 */

namespace App\Services;
use Carbon\Carbon;

class StatisticsService
{
    private $from_date;
    private $to_date;

    /**
     * @param array $args
     * @return array
     */
    public function get_statistics(array $args)
    {
        if (isset($args['from_date']) && isset($args['to_date'])) {
            $this->from_date = Carbon::parse($args['from_date']);
            $this->to_date = Carbon::parse($args['to_date']);
        } else {
            $this->from_date = Carbon::now()->subMonths(3);
            $this->to_date = Carbon::now();
        }
        $data_statistics = DataTransactionService::total_transaction_statistics($this->from_date, $this->to_date);
        $airtime_statistics = AirtimeTransactionService::total_transaction_statistics($this->from_date, $this->to_date);
        $user_statistics = CreateUserService::total_transaction_statistics($this->from_date, $this->to_date);
        $cable_statistics = CableTransactionService::total_transaction_statistics($this->from_date, $this->to_date);
        $power_statistics = ElectricityTransactionService::total_transaction_statistics($this->from_date, $this->to_date);
        $airtime_to_wallet_statistics = AirtimeToWalletTransactionService::total_transaction_statistics($this->from_date, $this->to_date);
        $wallet_fund = WalletTransactionService::total_online_wallet_funding($this->from_date, $this->to_date);

        $statistics = [ 'total_withdrawal' => WithdrawFundTransactionService::total_transaction_statistics($this->from_date, $this->to_date), 'total_coupon_funding' => 0,

        ];
        return array_merge($data_statistics, $airtime_statistics, $statistics, $user_statistics, $cable_statistics, $power_statistics, $airtime_to_wallet_statistics,$wallet_fund);

    }

    /**
     * @param $data
     * @return float
     */
    static public function sum_transaction($data)
    {
        $sum =0.0;
        foreach ($data as $datum){
            $sum += $datum->amount;
        }

        return $sum;
    }
}