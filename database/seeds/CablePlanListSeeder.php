<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CablePlanListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cable_plan_lists')->insert([
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv LITE',
                'amount' => '400',
                'product_code' => 'GOLITE',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Value',
                'amount' => '1250',
                'product_code' => 'GOTV',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Plus',
                'amount' => '1900',
                'product_code' => 'GOTVPLS',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Max',
                'amount' => '2600',
                'product_code' => 'GOTVMAX',
                'created_at' => Carbon::now()->toDateTimeString()
            ],




































            //dstv plans
            [
                'cable' => 'DSTV',
                'plan' => 'DStv FTA Plus',
                'amount' => '1600',
                'product_code' => 'FTAE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Access',
                'amount' => '2000',
                'product_code' => 'ACSSE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Family',
                'amount' => '4000',
                'product_code' => 'COFAME36',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'Asian Bouqet',
                'amount' => '5400',
                'product_code' => 'ASIAE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact',
                'amount' => '6800',
                'product_code' => 'COMPE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact Plus',
                'amount' => '10650',
                'product_code' => 'COMPLE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium',
                'amount' => '15800',
                'product_code' => 'PRWE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium Asia + HDPVR/XtraView',
                'amount' => '19900',
                'product_code' => 'DPRHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium + HDPVR/XtraView',
                'amount' => '18000',
                'product_code' => 'DPRHDP',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium + French Touch + HDPVR/XtraView',
                'amount' => '19470',
                'product_code' => 'DPRFRH',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium + French Touch',
                'amount' => '17270',
                'product_code' => 'DPRFR',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Family + HDPVR/XtraView',
                'amount' => '6200',
                'product_code' => 'DCOHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Family + Asia Add-on',
                'amount' => '9400',
                'product_code' => 'DCOAS',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact + HDPVR/XtraView',
                'amount' => '9000',
                'product_code' => 'DCOHDP',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact + French Touch',
                'amount' => '8270',
                'product_code' => 'DCOFR',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact Plus + HDPVR/XtraView',
                'amount' => '12850',
                'product_code' => 'DCOHDPV',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact Plus + Asian Add-on',
                'amount' => '14320',
                'product_code' => 'DCOFRHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ],




            //
            [
                'cable' => 'DSTV',
                'plan' => 'Asian Bouqet + HDPVR/XtraView',
                'amount' => '7600',
                'product_code' => 'DASHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Asian Add-on',
                'amount' => '5400',
                'product_code' => 'ASIADDE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Nova',
                'amount' => '900',
                'product_code' => 'STARN',

                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Basic',
                'amount' => '1300',
                'product_code' => 'STARB',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Smart',
                'amount' => '1900',
                'product_code' => 'STARS',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Classic',
                'amount' => '2600',
                'product_code' => 'STARC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Unique ',
                'amount' => '3800',
                'product_code' => 'STARU',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
        ]);
    }
}
