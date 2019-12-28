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
            'phone' => '09096006817',
            'email' => 'qhodeweb@gmail.com',
        ],
    ]);
    }
}
