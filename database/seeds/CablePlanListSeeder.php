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
                'plan' => 'Gotv Max ',
                'amount' => '2600',
                'product_code' => 'GOTVMAX',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Access',
                'amount' => '2000',
                'product_code' => 'ACSSE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Family',
                'amount' => '4000',
                'product_code' => 'COFAME36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Compact ',
                'amount' => '6800',
                'product_code' => 'COMPE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Compact Plus ',
                'amount' => '10650',
                'product_code' => 'COMPLE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Premium',
                'amount' => '15800',
                'product_code' => 'PRWE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Premium Asia ',
                'amount' => '19900',
                'product_code' => 'DPRHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Asian Bouqet',
                'amount' => '7600',
                'product_code' => 'DASHD',
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
