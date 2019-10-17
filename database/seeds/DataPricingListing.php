<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class DataPricingListing extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('data_price_listings')->insert([
            [
            'network' => 'GLO',
            'plan' => '1.6 or 2GB-30days SME',
            'price' => '950',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '3.65 or 4.5GB-30days SME',
            'price' => '1850',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '5.75 or 7.2GB-30days SME',
            'price' => '2350',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '7 or 8.25GB-30days SME',
            'price' => '2800',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '10 or 12.5GB-30days SME',
            'price' => '3700',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '12.5 or 15.6GB-30days SME',
            'price' => '4650',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '20 or 25GB-30days SME',
            'price' => '7400',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '26 or 32.5GB-30days SME',
            'price' => '8800',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '42 or 52.5GB-30days SME',
            'price' => '13200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '80MB-24hrs',
            'price' => '100',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '210MB-5days',
            'price' => '200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '800MB-14days',
            'price' => '500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '1.6GB-30days',
            'price' => '1000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '3.65GB-30days',
            'price' => '2000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '5.75GB-30days',
            'price' => '2500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '7GB-30days',
            'price' => '3000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '10GB-30days',
            'price' => '4000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '12.5GB-30days',
            'price' => '5000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'GLO',
            'plan' => '20GB-30days',
            'price' => '8000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '250MB-30days SME',
            'price' => '300',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '500MB-30days SME',
            'price' => '450',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '1.5GB-30days SME',
            'price' => '1100',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '2GB-30days SME',
            'price' => '1350',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '3GB-30days SME',
            'price' => '2000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '5GB-30days SME',
            'price' => '3350',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '11.5GB-30days SME',
            'price' => '7150',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '150MB-7days',
            'price' => '200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '1GB-30days',
            'price' => '1000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '1.5GB-30days',
            'price' => '1200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '2.5GB-30days',
            'price' => '2000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => '9MOBILE',
            'plan' => '3.5GB-30days',
            'price' => '2500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '250MB-90days SME',
            'price' => '250',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '500MB-90days SME',
            'price' => '500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '1GB-90days SME',
            'price' => '750',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '2GB-90days SME',
            'price' => '1300',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '3GB-90days SME',
            'price' => '2100',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '4GB-90days SME',
            'price' => '2700',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '5GB-90days SME',
            'price' => '3200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '50MB-1day',
            'price' => '100',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '750MB-14days',
            'price' => '500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '1GB-30days',
            'price' => '1000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '1.5GB-30days',
            'price' => '1200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '2.5GB-30days',
            'price' => '2000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '5GB-30days',
            'price' => '3500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'MTN',
            'plan' => '22GB-30days',
            'price' => '10000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '1.5GB-30days SME',
            'price' => '950',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '3.5GB-30days SME',
            'price' => '1950',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '5GB-30days SME',
            'price' => '2400',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '50MB-1day',
            'price' => '100',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '100MB-3days',
            'price' => '200',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '750MB-14days',
            'price' => '500',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '1.5GB-30days',
            'price' => '1000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
           'network' => 'AIRTEL',
            'plan' => '3.5GB-30days',
            'price' => '2000',
            'created_at' => Carbon::now()->toDateTimeString()
            ],
            [
            'network' => 'AIRTEL',
            'plan' => '5GB-30days',
            'price' => '2500',
            'created_at' => Carbon::now()->toDateTimeString()
            ]
            ]);
                }
}
