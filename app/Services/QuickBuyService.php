<?php


namespace App\Services;


use App\Events\QuickBuyEvent;
use App\Http\Controllers\UserController;
use App\Repositories\QuickBuyRepository;

class QuickBuyService
{
    /**
     * @var QuickBuyRepository
     */
    private $quick_buy_repository;

    /**
     * QuickBuyService constructor.
     * @param QuickBuyRepository $quick_buy_repository
     */
    public function __construct(QuickBuyRepository $quick_buy_repository)
    {
        $this->quick_buy_repository = $quick_buy_repository;
    }


    /**
     * @param array $quickBuy
     * @return \App\QuickBuy
     */
    public function create(array  $quickBuy)
    {
        $user_cont = New UserController();
        $quick_buy = $this->quick_buy_repository->create($quickBuy);
        $user = $quickBuy["email"];
        $admin = $user_cont->getAdmin();
        event(new QuickBuyEvent($quick_buy, $user, $admin));

        return $quick_buy;
    }

}