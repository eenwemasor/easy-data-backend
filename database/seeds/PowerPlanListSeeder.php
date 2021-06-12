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
                'description' => 'Abuja Electric',
                'disco'=>'AEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Kano Electric',
                'disco'=>'KEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Enugu Electric',
                'disco'=>'EEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Port Harcourt Electric',
                'disco'=>'PHEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Ibadan Electric',
                'disco'=>'IBEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'description' => 'Kaduna Electric',
                'disco'=>'KAEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],



            [
                'description' => 'Jos Electric',
                'disco'=>'JEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],


            [
                'description' => 'Benin Electric',
                'disco'=>'BEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'description' => 'Yola Electric',
                'disco'=>'YEDC',
                'created_at' => Carbon::now()->toDateTimeString()
            ],

        ]);
    }
}
