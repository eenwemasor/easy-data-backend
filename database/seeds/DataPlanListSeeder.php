<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPlanListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('data_plan_lists')->insert([
            [
                'network' => 'GLO',
                'plan' => '25MB (AR)',
                'price' => '20',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '242MB',
                'price' => '180',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '800MB/920MB',
                'price' => '400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '1.6GB/1.8GB',
                'price' => '400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '3.65GB/4.5GB',
                'price' => '400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '5.75GB/7.2GB',
                'price' => '1700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '7GB/8.75GB',
                'price' => '1700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '10GB/12.5GB',
                'price' => '3200',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '12.5B/15.5GB',
                'price' => '4000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '1GB',
                'price' => '900',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '1.5GB',
                'price' => '1080',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '2.5GB',
                'price' => '1800',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '4GB',
                'price' => '2700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '5.5GB',
                'price' => '3600',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '11.5GB',
                'price' => '7200',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => '9MOBILE',
                'plan' => '15GB',
                'price' => '9000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '500MB',
                'price' => '250',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '1GB',
                'price' => '450',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '2GB',
                'price' => '900',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '3GB',
                'price' => '1300',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '5GB',
                'price' => '2150',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '6GB',
                'price' => '2400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '10GB',
                'price' => '3400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '15GB',
                'price' => '4850',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'MTN',
                'plan' => '40GB',
                'price' => '9700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'AIRTEL',
                'plan' => '750MB (2Weeks)',
                'price' => '470',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'AIRTEL',
                'plan' => '1.5GB',
                'price' => '950',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'AIRTEL',
                'plan' => '4.5GB',
                'price' => '1950',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'AIRTEL',
                'plan' => '6GB',
                'price' => '2400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'AIRTEL',
                'plan' => '8GB',
                'price' => '2900',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'AIRTEL',
                'plan' => '11GB',
                'price' => '3900',
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]);
    }
}
