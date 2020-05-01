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
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '242MB',
                'amount' => '180',
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '800MB',
                'amount' => '400',
                'product_code'=>'*127*57*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '1.6GB',
                'amount' => '400',
                'product_code'=>'*127*53*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '3.65GB',
                'amount' => '400',
                'product_code'=>'*127*55*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '5.75GB',
                'amount' => '1700',
                'product_code'=>'*127*58*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '7GB',
                'amount' => '1700',
                'product_code'=>'*127*54*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '10GB',
                'amount' => '3200',
                'product_code'=>'*127*59*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::GLO,
                'plan' => '12.5B',
                'amount' => '4000',
                'product_code'=>'*127*2*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '1.5GB',
                'amount' => '900',
                'product_code'=>'*229*2*7*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '2GB',
                'amount' => '1080',
                'product_code'=>'*229*2*25*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '3GB',
                'amount' => '1800',
                'product_code'=>'*229*2*3*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '4.5GB',
                'amount' => '2700',
                'product_code'=>'*229*2*8*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '11GB',
                'amount' => '3600',
                'product_code'=>'*229*2*36*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '15GB',
                'amount' => '7200',
                'product_code'=>'229*2*37*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '40GB',
                'amount' => '9000',
                'product_code'=>'229*4*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ]
            ,
            [
                'network' => NetworkType::NINE_MOBILE,
                'plan' => '75GB',
                'amount' => '12000',
                'product_code'=>'229*2*4*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '500MB',
                'amount' => '250',
                'product_code'=>'SMEB',
                'vendor_amount'=>'500',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '1GB',
                'amount' => '450',
                'product_code'=>'SMEC',
                'vendor_amount'=>'1000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '2GB',
                'amount' => '900',
                'product_code'=>'SMED',
                'vendor_amount'=>'2000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '3GB',
                'amount' => '1300',
                'product_code'=>'SMEF',
                'vendor_amount'=>'3000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '5GB',
                'amount' => '2150',
                'product_code'=>'SMEE',
                'vendor_amount'=>'5000',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '6GB',
                'amount' => '2400',
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '10GB',
                'amount' => '3400',
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '15GB',
                'amount' => '4850',
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::MTN,
                'plan' => '40GB',
                'amount' => '9700',
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '750MB',
                'amount' => '470',
                'product_code'=>'141*6*2*2*3*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '1.5GB',
                'amount' => '950',
                'product_code'=>'141*6*2*1*7*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '2GB',
                'amount' => '1150',
                'product_code'=>'141*6*2*1*6*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '3GB',
                'amount' => '1450',
                'product_code'=>'141*6*2*1*5*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '4.5GB',
                'amount' => '1950',
                'product_code'=>'141*6*2*1*4*1*',
                'vendor_amount'=>'null',
                'created    _at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '6GB',
                'amount' => '2400',
                'product_code'=>'141*6*2*1*3*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '8GB',
                'amount' => '2900',
                'product_code'=>'141*6*2*1*2*1*',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'network' => NetworkType::AIRTEL,
                'plan' => '11GB',
                'amount' => '3900',
                'product_code'=>'null',
                'vendor_amount'=>'null',
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]);
    }
}
