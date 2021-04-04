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
        $this->call(AccountLevelSeeder::class);
        $this->call(DataPlanListSeeder::class);
        $this->call(CablePlanListSeeder::class);
        $this->call(ReferralRewardSeeder::class);
        $this->call(AdminChannelUtilSeeder::class);
        $this->call(PowerPlanListSeeder::class);
        $this->call(ResultCheckerSeeder::class);
        $this->call(AccountLevelApplicablesSeeder::class);
        $this->call(SmilePriceListSeeder::class);
        $this->call(SpectranetPriceListSeeder::class);
        $this->call(BankSeeder::class);

        factory(\App\AirtimeTransaction::class, 20)->create();
        factory(\App\CableTransaction::class, 20)->create();
        factory(\App\DataTransaction::class, 20)->create();
        factory(\App\ElectricityTransaction::class, 20)->create();
        factory(\App\WalletTransaction::class, 20)->create();
        factory(\App\User::class, 300)->create();
        factory(\App\ResultCheckTransaction::class, 20)->create();
        factory(\App\ResultCheckerPin::class, 1000)->create();
        factory(\App\NewsFeed::class, 3)->create();
        factory(\App\NewsUpdate::class, 1)->create();


        DB::table('users')->insert([
            'full_name' => "Subpay Admin",
            'email' => "admin@subpay.com.ng",
            'phone' => "09096006817",
            'wallet' => 0, // wallet
            'accessibility' => "ADMIN",
            'email_confirmed' => true,
            'phone_verified' => true,
            'account_level_id' => '1',
            'unique_id' => 'sjdfsdkjfdslksdfodsfoisd',
            'active' => true,
            'username'=>'admin20',
            'bonus_wallet' => 0,
            'monnify_account_number'=>"000000000000000",
            'monnify_bank_name'=>"Providus Bank",
            'monnify_bank_code'=>"101",
            'monnify_collection_channel'=>"RESERVED_ACCOUNT",
            'monnify_reservation_channel'=>"4PKHSNPCYBUAYZ8C9BFM",
            'password' => bcrypt('Admin'),
        ]);


        DB::table('users')->insert([
            'full_name' => "Subpay User1",
            'email' => "user1@subpay.com.ng",
            'phone' => "09090000000",
            'wallet' => 0, // wallet
            'accessibility' => "USER",
            'email_confirmed' => true,
            'phone_verified' => true,
            'unique_id' => '093248438439843239',
            'active' => true,
            'account_level_id' => '1',
            'username'=>'user1',
            'bonus_wallet' => 0,
            'monnify_account_number'=>"000000000000000",
            'monnify_bank_name'=>"Providus Bank",
            'monnify_bank_code'=>"101",
            'monnify_collection_channel'=>"RESERVED_ACCOUNT",
            'monnify_reservation_channel'=>"4PKHSNPCYBUAYZ8C9BFM",
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'full_name' => "Subpay2 User2",
            'email' => "user2@subpay.com.ng",
            'phone' => "09090000000",
            'wallet' => 0, // wallet
            'accessibility' => "USER",
            'email_confirmed' => true,
            'phone_verified' => true,
            'account_level_id' => '1',
            'unique_id' => '093248438439843239',
            'active' => true,
            'username'=>'user2',
            'bonus_wallet' => 0,
            'monnify_account_number'=>"000000000000000",
            'monnify_bank_name'=>"Providus Bank",
            'monnify_bank_code'=>"101",
            'monnify_collection_channel'=>"RESERVED_ACCOUNT",
            'monnify_reservation_channel'=>"4PKHSNPCYBUAYZ8C9BFM",
            'password' => bcrypt('password'),
        ]);

    }
}
