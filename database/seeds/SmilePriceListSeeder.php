<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmilePriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('smile_price_lists')->insert([
            [
                'description' => 'FlexiDaily plan - Diverse Validity',
                'product_code' => 'FPDV356',
                'vendor_price' => '500',
                'price' => '500'
            ],
            [
                'description' => 'FlexiWeekly plan - Diverse Validity',
                'product_code' => 'FPDV357',
                'vendor_price' => '1200',
                'price' => '1200'
            ],
            [
                'description' => 'SmileVoice ONLY 75 - 30days Validity',
                'product_code' => 'SOV495',
                'vendor_price' => '500',
                'price' => '500'
            ],
            [
                'description' => 'SmileVoice ONLY 500 - 30days Validity',
                'product_code' => 'SOV497',
                'vendor_price' => '3000',
                'price' => '3000'
            ],
            [
                'description' => 'SmileVoice ONLY 165 - 30days Validity',
                'product_code' => 'SOV496',
                'vendor_price' => '1000',
                'price' => '1000'
            ],
            [
                'description' => '1GB SmileLite plan - 30days Validity',
                'product_code' => 'SPV220',
                'vendor_price' => '1000',
                'price' => '1000'
            ],
            [
                'description' => '2GB SmileLite plan - 30days Validity',
                'product_code' => 'SPV280',
                'vendor_price' => '2000',
                'price' => '2000'
            ],
            [
                'description' => '2GB MidNite Plan - 7days Validity',
                'product_code' => 'MPV413',
                'vendor_price' => '1000',
                'price' => '1000'
            ],
            [
                'description' => '3GB MidNite Plan - 7days Validity',
                'product_code' => 'MPV414',
                'vendor_price' => '1500',
                'price' => '1500'
            ],
            [
                'description' => '3GB Weekend Only Plan - 3days Validity',
                'product_code' => 'WOPV415',
                'vendor_price' => '1500',
                'price' => '1500'
            ],
            [
                'description' => '3GB Anytime plan - 7days Validity',
                'product_code' => 'APV102',
                'vendor_price' => '3000',
                'price' => '3000'
            ],
            [
                'description' => '5GB Anytime plan - 7days Validity',
                'product_code' => 'APV150',
                'vendor_price' => '4000',
                'price' => '4000'
            ],
            [
                'description' => '7GB Anytime plan - 7days Validity',
                'product_code' => 'APV274',
                'vendor_price' => '5000',
                'price' => '5000'
            ],
            [
                'description' => '10GB 30 Days Anytime plan - 7days Validity',
                'product_code' => 'DAPV404',
                'vendor_price' => '7500',
                'price' => '7500'
            ],
            [
                'description' => 'Unlimited Lite Plan - 30days Validity',
                'product_code' => 'ULPV476',
                'vendor_price' => '10000',
                'price' => '10000'
            ],
            [
                'description' => 'Unlimited Premium Plan - 30days Validity',
                'product_code' => 'UPPV475',
                'vendor_price' => '19800',
                'price' => '19800'
            ],
            [
                'description' => '30GB BumpaValue plan - Diverse Validity',
                'product_code' => 'BPDV358',
                'vendor_price' => '15000',
                'price' => '15000'
            ],
            [
                'description' => '60GB BumpaValue plan - Diverse Validity',
                'product_code' => 'BPDV359',
                'vendor_price' => '30000',
                'price' => '30000'
            ],
            [
                'description' => '80GB BumpaValue plan - Diverse Validity',
                'product_code' => 'BPDV360',
                'vendor_price' => '50000',
                'price' => '50000'
            ],
            [
                'description' => '10GB Anytime plan - 365days Validity',
                'product_code' => 'APV103',
                'vendor_price' => '9000',
                'price' => '9000'
            ],
            [
                'description' => '15GB Anytime plan - 365days Validity',
                'product_code' => 'APV273',
                'vendor_price' => '10000',
                'price' => '10000'
            ],
            [
                'description' => '20GB Anytime plan - 365days Validity',
                'product_code' => 'APV104',
                'vendor_price' => '17000',
                'price' => '17000'
            ],
            [
                'description' => '50GB Anytime plan - 365days Validity',
                'product_code' => 'APV105',
                'vendor_price' => '36000',
                'price' => '36000'
            ],
            [
                'description' => '100GB Anytime plan - 365days Validity',
                'product_code' => 'APV128',
                'vendor_price' => '70000',
                'price' => '70000'
            ],
            [
                'description' => '200GB Anytime plan - 365days Validity',
                'product_code' => 'APV129',
                'vendor_price' => '135000',
                'price' => '135000'
            ],
        ]);
    }
}
