<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(DataPricingListing::class);
        $this->call(CableSubscriptions::class);
        $this->call(ReferralReward::class);
        $this->call(AdminChannelUtil::class);
    }
}
