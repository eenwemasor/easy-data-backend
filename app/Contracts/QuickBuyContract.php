<?php


namespace App\Contracts;


use App\QuickBuy;

interface QuickBuyContract
{
    /**
     * @param array $QuickBuy
     * @return QuickBuy
     */
    public function create(array $QuickBuy):QuickBuy;
}