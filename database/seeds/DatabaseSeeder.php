<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        $this->call(DataPlanListSeeder::class);
        $this->call(CablePlanListSeeder::class);
        $this->call(ReferralReward::class);
        $this->call(AdminChannelUtil::class);
        $this->call(PowerPlanListSeeder::class);


        factory(\App\AirtimeTransaction::class, 20)->create();
        factory(\App\CableTransaction::class, 20)->create();
        factory(\App\DataTransaction::class, 20)->create();
        factory(\App\ElectricityTransaction::class, 20)->create();
        factory(\App\WalletTransaction::class, 20)->create();
        factory(\App\User::class, 3)->create();


        DB::table('users')->insert([
            'full_name' => "EasyData Admin",
            'email' => "qhodeweb@gmail.com",
            'phone' => "09096006817",
            'wallet' => 0, // password
            'accessibility' => "admin",
            'email_confirmed' => true,
            'phone_verified' => true,
            'unique_id' => 'sjdfsdkjfdslksdfodsfoisd',
            'active' => true,
            'bonus_wallet' => 0,
            'password' => bcrypt('Admin'),
        ]);


    }
}
