<?php

use App\Enums\NetworkType;
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
                'network' =>NetworkType::GLO,
                'plan' => '25MB (AR)',
                'amount' => '20',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '242MB',
                'amount' => '180',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => 'GLO',
                'plan' => '800MB/920MB',
                'amount' => '400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '1.6GB/1.8GB',
                'amount' => '400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '3.65GB/4.5GB',
                'amount' => '400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '5.75GB/7.2GB',
                'amount' => '1700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '7GB/8.75GB',
                'amount' => '1700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '10GB/12.5GB',
                'amount' => '3200',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '12.5B/15.5GB',
                'amount' => '4000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '1GB',
                'amount' => '900',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '1.5GB',
                'amount' => '1080',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '2.5GB',
                'amount' => '1800',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
               'network' => NetworkType::NINE_MOBILE,
                'plan' => '4GB',
                'amount' => '2700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '5.5GB',
                'amount' => '3600',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '11.5GB',
                'amount' => '7200',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '15GB',
                'amount' => '9000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '500MB',
                'amount' => '250',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '1GB',
                'amount' => '450',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '2GB',
                'amount' => '900',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '3GB',
                'amount' => '1300',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '5GB',
                'amount' => '2150',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '6GB',
                'amount' => '2400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '10GB',
                'amount' => '3400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '15GB',
                'amount' => '4850',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '40GB',
                'amount' => '9700',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '750MB (2Weeks)',
                'amount' => '470',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '1.5GB',
                'amount' => '950',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '4.5GB',
                'amount' => '1950',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '6GB',
                'amount' => '2400',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '8GB',
                'amount' => '2900',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '11GB',
                'amount' => '3900',
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]);
    }
}
