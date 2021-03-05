<?php

use App\Enums\ResultCheckerExamBody;
use Illuminate\Database\Seeder;

class ResultCheckerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('result_checkers')->insert([
            [
                'examination_body' => ResultCheckerExamBody::WAEC,
                'price' => 750,
                'vendor_price'=>750,
                'product_code' => 'WRCONE',
                'description' => 'One piece of waec result checker'

            ],
            [
                'examination_body' => ResultCheckerExamBody::WAEC,
                'price' => 1500,
                'vendor_price'=>1500,
                'product_code' => 'WRCTWO',
                'description' => 'Two piece of waec result checker'
            ],
            [
                'examination_body' => ResultCheckerExamBody::WAEC,
                'price' => 2250,
                'vendor_price'=>2250,
                'product_code' => 'WRCTHR',
                'description' => 'Three  piece of waec result checker'
            ],
            [
                'examination_body' => ResultCheckerExamBody::WAEC,
                'price' => 3000,
                'vendor_price'=>3000,
                'product_code' => 'WRCFOU',
                'description' => 'Four piece of waec result checker'
            ],
            [
                'examination_body' => ResultCheckerExamBody::WAEC,
                'price' => 3750,
                'vendor_price'=>3750,
                'product_code' => 'WRCFIV',
                'description' => 'Five piece of waec result checker'
            ],
//            Neco result checker packages
            [
                'examination_body' => ResultCheckerExamBody::NECO,
                'price' => 650,
                'vendor_price'=>650,
                'product_code' => 'NECONE',
                'description' => 'One piece of neco result checker'
            ],

            [
                'examination_body' => ResultCheckerExamBody::NECO,
                'price' => 1300,
                'vendor_price'=>1300,
                'product_code' => 'NECTWO',
                'description' => 'Two piece of neco result checker'
            ],
            [
                'examination_body' => ResultCheckerExamBody::NECO,
                'price' => 1950,
                'vendor_price'=>1950,
                'product_code' => '	NECTHR',
                'description' => 'Three  piece of neco result checker'
            ],
            [
                'examination_body' => ResultCheckerExamBody::NECO,
                'price' => 2600,
                'vendor_price'=>2600,
                'product_code' => 'NECFOU',
                'description' => 'Four piece of neco result checker'
            ],
            [
                'examination_body' => ResultCheckerExamBody::WAEC,
                'price' => 3250,
                'vendor_price'=>3250,
                'product_code' => 'NECFIV',
                'description' => 'Five piece of neco result checker'
            ],
        ]);
    }
}
