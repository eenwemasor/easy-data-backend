<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CableSubscriptions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cable_subscriptions')->insert([
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Value',
                'price' => '1250',
                'product_code' => 'GOTV',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Plus',
                'price' => '1900',
                'product_code' => 'GOTVPLS',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'GOTV',
                'plan' => 'Gotv Max ',
                'price' => '2600',
                'product_code' => 'GOTVMAX',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Access',
                'price' => '2000',
                'product_code' => 'ACSSE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Family',
                'price' => '4000',
                'product_code' => 'COFAME36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Compact ',
                'price' => '6800',
                'product_code' => 'COMPE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Compact Plus ',
                'price' => '10650',
                'product_code' => 'COMPLE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Premium',
                'price' => '15800',
                'product_code' => 'PRWE36',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Premium Asia ',
                'price' => '19900',
                'product_code' => 'DPRHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'DSTV',
                'plan' => 'Dstv Asian Bouqet',
                'price' => '7600',
                'product_code' => 'DASHD',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Nova',
                'price' => '900',
                'product_code' => 'STARN',

                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Basic',
                'price' => '1300',
                'product_code' => 'STARB',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Smart',
                'price' => '1900',
                'product_code' => 'STARS',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Classic',
                'price' => '2600',
                'product_code' => 'STARC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'cable' => 'STARTIMES',
                'plan' => 'Startime Unique ',
                'price' => '3800',
                'product_code' => 'STARU',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
        ]);
    }
}
