<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminChannelUtil extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { DB::table('admin_channel_utils')->insert([
        [
            'phone' => '+2349096006817',
            'email' => 'qhodeweb@gmail.com',
            'statement_request_charge'=> '20',
            'glo_discount'=> '3',
            'airtel_discount'=> '2',
            'mtn_discount'=> '2',
            'etisalat_discount'=> '2',
            'bitcoin_buying_rate'=>'340',
            'giftcard_buying_rate'=>'300',
            'data_pin'=>'1989',
            'paystack_transaction_fee'=>0,
            'rave_transaction_fee'=>0,
            'trade_airtime_recipient_number_glo'=>'07052838372',
            'trade_airtime_recipient_number_airtel'=>'08172838372',
            'trade_airtime_recipient_number_etisalat'=>'09092838372',
            'trade_airtime_recipient_number_mtn'=>'08063838372',
            'sms_unit_charge'=>"2.9"
        ],
    ]);
    }
}
