<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PowerPlanListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('power_plan_lists')->insert([
            [
                'description' => 'Ikeja Electricity Bill Payment',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Ikeja Electricity Token Purchase',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Eko Electricity Distribution Prepaid',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Eko Electricity Distribution Postpaid',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Kano Electricity Distribution Prepaid',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Kano Electricity Distribution Postpaid',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Abuja Prepaid',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Abuja Postpaid',
                'created_at' => Carbon::now()->toDateTimeString()
            ],

        ]);
    }
}
