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
                'cost_to_upgrade' => 0
            ],
            [
                'name' => "Paid",
                'cost_to_upgrade' => 1000
            ],
        ]);
    }
}
