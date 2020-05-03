<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bank_accounts')->insert([
            [
                'name' =>'Bank One Name',
                'bank_name' => 'UBA Bank',
                'bank_number' => '092399393223',
            ],

            [
                'name' =>'Bank Two Name',
                'bank_name' => 'Zenith Bank',
                'bank_number' => '092399393223',
            ],

            [
                'name' =>'Bank Three Name',
                'bank_name' => 'Sterling Bank',
                'bank_number' => '092399393223',
            ],
        ]);
    }
}
