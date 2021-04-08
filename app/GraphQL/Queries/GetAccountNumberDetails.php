<?php

namespace App\GraphQL\Queries;

use App\Gateways\Paystack;

class GetAccountNumberDetails
{
    /**
     * @var Paystack
     */
    private $paystack;

    /**
     * GetAccountNumberDetails constructor.
     * @param Paystack $paystack
     */
    public function __construct(Paystack $paystack)
    {

        $this->paystack = $paystack;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $accountDetails =  $this->paystack->verify_bank_account($args);

        return [
            'account_number'=> $accountDetails->account_number,
            'account_name'=>$accountDetails->account_name,
            'bank_id'=>$accountDetails->bank_id
        ];
    }
}
