<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_levels')->insert([
            [
                'name' => "Free",
                'cost_to_upgrade' => 0,
                'direct_referrer_percentage_bonus' => 30,
                'indirect_referrer_percentage_bonus' => 5,
                'wallet_deposit_direct_referrer_percentage_bonus' => 10,
                'wallet_deposit_indirect_referrer_percentage_bonus' => 10
            ],
            [
                'name' => "Paid",
                'cost_to_upgrade' => 1000,
                'direct_referrer_percentage_bonus' => 50,
                'indirect_referrer_percentage_bonus' => 10,
                'wallet_deposit_direct_referrer_percentage_bonus' => 10,
                'wallet_deposit_indirect_referrer_percentage_bonus' => 10
            ],
        ]);
    }
}
