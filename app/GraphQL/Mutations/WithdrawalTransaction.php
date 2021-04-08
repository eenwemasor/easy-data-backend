<?php

namespace App\GraphQL\Mutations;

use App\Services\WithdrawalTransactionService;

class WithdrawalTransaction
{
    /**
     * @var WithdrawalTransactionService
     */
    private  $withdrawalTransactionService;

    /**
     * WithdrawalTransaction constructor.
     * @param WithdrawalTransactionService $withdrawalTransactionService
     */
    public function __construct(WithdrawalTransactionService $withdrawalTransactionService)
    {
        $this->withdrawalTransactionService = $withdrawalTransactionService;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function resolve($_, array $args)
    {
       return $this->withdrawalTransactionService->create($args);
    }
}
