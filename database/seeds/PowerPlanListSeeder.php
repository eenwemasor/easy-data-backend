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
                'disco'=>'IKEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Eko Electric',
                'disco'=>'EKEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Kano Electric',
                'disco'=>'KEDCO',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Port Harcourt Electric',
                'disco'=>'PHED',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Ibadan Electric',
                'disco'=>'IBEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'description' => 'Jos Electric',
                'disco'=>'JED',
                'created_at' => Carbon::now()->toDateTimeString()
            ],

        ]);
    }
}
