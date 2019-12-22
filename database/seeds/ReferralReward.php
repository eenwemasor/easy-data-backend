<?php

use Illuminate\Database\Seeder;

class ReferralReward extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('referral_rewards')->insert([
            [
                'referrer_percentage' => '10',
                'indirect_referrer_percentage' => '5',
                'referee_percentage' => '20',
                'widget' =>"200"
            ],
        ]);
    }
}
