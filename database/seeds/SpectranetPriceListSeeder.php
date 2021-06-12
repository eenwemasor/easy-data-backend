<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpectranetPriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spectranet_price_lists')->insert([
            [
                'description' => '₦1,000 Spectranet Pin',
                'product_code' => 'SPEC10',
                'vendor_price' => '1000',
                'price' => '1000'
            ],
            [
                'description' => '₦2,000 Spectranet Pin',
                'product_code' => 'SPEC20',
                'vendor_price' => '2000',
                'price' => '2000'
            ],
            [
                'description' => '₦5,000 Spectranet Pin',
                'product_code' => 'SPEC50',
                'vendor_price' => '5000',
                'price' => '5000'
            ],
            [
                'description' => '₦7,000 Spectranet Pin',
                'product_code' => 'SPEC70',
                'vendor_price' => '7000',
                'price' => '7000'
            ],
            [
                'description' => '₦10,000 Spectranet Pin',
                'product_code' => 'SPEC100',
                'vendor_price' => '10000',
                'price' => '10000'
            ],
        ]);
    }
}
