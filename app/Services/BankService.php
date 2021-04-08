<?php


namespace App\Services;


use App\Bank;
use App\BankAccount;
use App\Gateways\Paystack;

class BankService
{
    /**
     * @var Paystack
     */
    private $paystack;

    /**
     * BankService constructor.
     * @param Paystack $paystack
     */
    public function __construct(Paystack $paystack)
    {
        $this->paystack = $paystack;
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \App\GraphQL\Errors\GraphqlError
     */
    public function create(array $args)
    {
        $bank = Bank::find($args['bank_id']);
        $this->paystack->verify_bank_account([
            'bank_number' => $args['bank_number'],
            'bank_code' => $bank->code
        ]);

        return  BankAccount::create($args);

    }
}
