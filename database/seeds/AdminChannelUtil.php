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
            'phone' => '+2348101005275',
            'email' => 'Rajdolla07@gmail.com',
            'statement_request_charge'=> '20',
            'glo_discount'=> '3',
            'airtel_discount'=> '2',
            'mtn_discount'=> '2',
            'etisalat_discount'=> '2',
            'paystack_transaction_fee'=>0,
            'sms_unit_charge'=>"2.9"

        ],
    ]);
    }
}
