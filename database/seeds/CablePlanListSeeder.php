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
                'vendor_identifier'=>'gotv-lite',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Value',
                'amount' => '1250',
                'product_code' => 'GOTV',
                'vendor_identifier'=>'gotv-value',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Plus',
                'amount' => '1900',
                'product_code' => 'GOTVPLS',
                'vendor_identifier'=>'gotv-plus',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Max',
                'amount' => '2600',
                'product_code' => 'GOTVMAX',
                'vendor_identifier'=>'gotv-max',
                'created_at' => Carbon::now()->toDateTimeString()
            ],




































            //dstv plans
            [
                'cable' => 'DSTV',
                'plan' => 'DStv German only',
                'amount' => '3640',
                'product_code' => 'GERMAN36',
                'vendor_identifier'=>'dstv4',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Access',
                'amount' => '2000',
                'product_code' => 'ACSSE36',
                'vendor_identifier'=>'dstv1',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Family',
                'amount' => '4000',
                'product_code' => 'COFAME36',
                'vendor_identifier'=>'dstv2',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact',
                'amount' => '6800',
                'product_code' => 'COMPE36',
                'vendor_identifier'=>'dstv79',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact Plus',
                'amount' => '10650',
                'product_code' => 'COMPLE36',
                'vendor_identifier'=>'dstv7',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium',
                'amount' => '15800',
                'product_code' => 'PRWE36',
                'vendor_identifier'=>'dstv3',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium Asia + HDPVR/XtraView',
                'amount' => '19900',
                'product_code' => 'DPRHD',
                'vendor_identifier'=>'dstv49',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Premium + HDPVR/XtraView',
                'amount' => '18000',
                'product_code' => 'DPRHDP',
                'vendor_identifier'=>'dstv34',
                'created_at' => Carbon::now()->toDateTimeString()
            ],  [
                'cable' => 'DSTV',
                'plan' => 'DStv Family + HDPVR/XtraView',
                'amount' => '6200',
                'product_code' => 'DCOHD',
                'vendor_identifier'=>'dstv26',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Family + Asia Add-on',
                'amount' => '9400',
                'product_code' => 'DCOAS',
                'vendor_identifier'=>'dstv18',
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact + HDPVR/XtraView',
                'amount' => '9000',
                'product_code' => 'DCOHDP',
                'vendor_identifier'=>'dstv29',
                'created_at' => Carbon::now()->toDateTimeString()
            ],  [
                'cable' => 'DSTV',
                'plan' => 'DStv Compact Plus + HDPVR/XtraView',
                'amount' => '12850',
                'product_code' => 'DCOHDPV',
                'vendor_identifier'=>'dstv45',
                'created_at' => Carbon::now()->toDateTimeString()
            ],




            //
            [
                'cable' => 'DSTV',
                'plan' => 'Asian Bouqet + HDPVR/XtraView',
                'amount' => '7600',
                'product_code' => 'DASHD',
                'vendor_identifier'=>'dstv78',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Nova',
                'amount' => '900',
                'product_code' => 'STARN',
                'vendor_identifier'=>'nova',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Basic',
                'amount' => '1300',
                'product_code' => 'STARB',
                'vendor_identifier'=>'basic',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Smart',
                'amount' => '1900',
                'product_code' => 'STARS',
                'vendor_identifier'=>'smart',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Classic',
                'amount' => '2600',
                'product_code' => 'STARC',
                'vendor_identifier'=>'classic',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Unique ',
                'amount' => '3800',
                'product_code' => 'STARU',
                'vendor_identifier'=>'super',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
        ]);
    }
}
