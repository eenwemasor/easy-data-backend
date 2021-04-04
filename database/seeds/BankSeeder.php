<?php

use App\Gateways\Paystack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paystack = new Paystack();
        foreach ($paystack->get_bank_list() as $bank){
            DB::table('banks')->insert([
                [
                    'name' => $bank->name,
                    'slug' => $bank->slug,
                    'code' => $bank->code,
                    'long_code' => $bank->longcode,
                    'gateway' => $bank->gateway,
                    'pay_with_bank' => $bank->pay_with_bank,
                    'active' => $bank->active,
                    'is_deleted' => $bank->is_deleted,
                    'country' => $bank->country,
                    'currency' => $bank->currency,
                    'type' => $bank->type,
                ],
            ]);
        }


    }
}
