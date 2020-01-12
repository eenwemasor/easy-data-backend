<?php

use Illuminate\Database\Seeder;

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
            'account_activation_amount'=> '1000',
            'glo_discount'=> '3',
            'airtel_discount'=> '2',
            'mtn_discount'=> '2',
            'etisalat_discount'=> '2'
        ],
    ]);
    }
}
