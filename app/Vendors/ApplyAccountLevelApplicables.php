<?php

namespace App\Vendors;
use App\Enums\ApplicationMethodType;
use App\Enums\CalculationMethodType;

class ApplyAccountLevelApplicables
{
    /**
     * @param float $amount
     * @param $applicables
     * @return float|int
     */
    public function apply_applicable(float $amount, $applicables)
    {
        $discountedAmount = $amount;
        foreach ($applicables as $applicable) {
            switch ($applicable->application_method) {
                case ApplicationMethodType::SERVICE_CHARGE:
                {
                    if ($applicable->calculation_method === CalculationMethodType::AMOUNT) {
                        $discountedAmount = $discountedAmount + $applicable->value;
                    } else {
                        $discountedAmount = $discountedAmount + (($discountedAmount / 100) * $applicable->value);
                    }
                    break;
                }
                case ApplicationMethodType::DISCOUNT:
                {
                    if ($applicable->calculation_method === CalculationMethodType::AMOUNT) {
                        $discountedAmount = $discountedAmount - $applicable->value;
                    } else {
                        $discountedAmount = $discountedAmount - (($discountedAmount / 100) * $applicable->value);
                    }
                    break;
                }
                default:
                {
                    break;
                }
            }
        }
        return $discountedAmount;
    }

}
