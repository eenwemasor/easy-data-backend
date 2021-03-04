<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminChannelUtilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_channel_utils')->insert([
            [
                'phone' => '+2348164776022',
                'email' => 'subpaycommunications@gmail.com',
                'statement_request_charge' => '20',
                'glo_discount' => '3',
                'airtel_discount' => '2',
                'mtn_discount' => '2',
                'etisalat_discount' => '2',
                'paystack_transaction_fee' => 0,
                'sms_unit_charge' => "2.9",
                'paystack_fund_wallet_fee' => 9,
                'cable_tv_service_charge' => 50,
                'electricity_service_charge' => 50,
                'monnify_bank_service_charge' => 10,
                'fund_wallet_min_amount' => 100,
                'fund_wallet_max_amount' => 2000
            ],
        ]);
    }
}
