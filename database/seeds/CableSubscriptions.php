<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
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
            'created_at' => Carbon::now()->toDateTimeString()
            ],
             [
            'cable' => 'GOTV',
            'plan' => 'Gotv Plus',
            'price' => '1900',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'GOTV',
            'plan' => 'Gotv Max ',
            'price' => '2600',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
             [
            'cable' => 'DSTV',
            'plan' => 'Dstv Access',
            'price' => '2000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'DSTV',
            'plan' => 'Dstv Family',
            'price' => '4000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'DSTV',
            'plan' => 'Dstv Compact ',
            'price' => '6800',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'DSTV',
            'plan' => 'Dstv Compact Plus ',
            'price' => '10650',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'DSTV',
            'plan' => 'Dstv Premium',
            'price' => '15800',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'DSTV',
            'plan' => 'Dstv Premium Asia ',
            'price' => '16530',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'DSTV',
            'plan' => 'Dstv Asian Bouqet',
            'price' => '5050',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'STARTIMES',
            'plan' => 'Startime Nova',
            'price' => '900',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'STARTIMES',
            'plan' => 'Startime Basic',
            'price' => '1300',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'STARTIMES',
            'plan' => 'Startime Smart',
            'price' => '1900',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'STARTIMES',
            'plan' => 'Startime Classic',
            'price' => '2600',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'cable' => 'STARTIMES',
            'plan' => 'Startime Unique ',
            'price' => '3800',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
         ]);
    }
}
