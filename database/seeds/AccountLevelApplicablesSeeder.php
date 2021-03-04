<?php

use App\Enums\ApplicationMethodType;
use App\Enums\CalculationMethodType;
use App\Enums\ServiceType;
use Illuminate\Database\Seeder;

class AccountLevelApplicablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_level_applicables')->insert([
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::AIRTIME,
                'application_method'=>ApplicationMethodType::NONE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::AIRTIME,
                'application_method'=>ApplicationMethodType::NONE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::DATA_DIRECT,
                'application_method'=>ApplicationMethodType::DISCOUNT,
                'calculation_method'=>CalculationMethodType::PERCENTAGE,
                'value' => 1,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::DATA_DIRECT,
                'application_method'=>ApplicationMethodType::DISCOUNT,
                'calculation_method'=>CalculationMethodType::PERCENTAGE,
                'value' => 2,
            ],
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::DATA_SME,
                'application_method'=>ApplicationMethodType::NONE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::DATA_SME,
                'application_method'=>ApplicationMethodType::NONE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::CABLE,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 20,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::CABLE,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::ELECTRICITY,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 20,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::ELECTRICITY,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ], [
                'account_level_id' => 1,
                'service_type' => ServiceType::SMILE,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 20,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::SMILE,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::SPECTRANET,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 20,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::SPECTRANET,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ],
            [
                'account_level_id' => 1,
                'service_type' => ServiceType::RESULT_CHECKER,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 20,
            ],
            [
                'account_level_id' => 2,
                'service_type' => ServiceType::RESULT_CHECKER,
                'application_method'=>ApplicationMethodType::SERVICE_CHARGE,
                'calculation_method'=>CalculationMethodType::AMOUNT,
                'value' => 0,
            ]

        ]);
    }
}
