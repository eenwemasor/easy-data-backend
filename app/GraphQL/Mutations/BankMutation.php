<?php

namespace App\GraphQL\Mutations;

use App\Services\BankService;

class BankMutation
{
    private $bankService;

    /**
     * BankMutation constructor.
     * @param BankService $bankService
     */
    public function __construct(BankService $bankService)
    {

        $this->bankService = $bankService;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function resolve($_, array $args)
    {
        return $this->bankService->create($args);
    }
}
