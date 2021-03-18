<?php


namespace App\Http\Controllers;

use App\AdminChannelUtil;
use App\Enums\TransactionType;
use App\ReferralReward;
use App\Repositories\CreateUserRepository;
use App\Repositories\WalletTransactionRepository;
use App\Services\CreateUserService;
use App\Services\WalletTransactionService;
use App\User;
use Illuminate\Http\Request;

class MonnifyController
{
    /**
     * @var CreateUserService
     */
    private $createUserService;

    /**
     * MonnifyController constructor.
     * @param CreateUserService $createUserService
     */
    public function __construct(CreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $this->createUserService->handle_monnify_deposit($request);

    }
}
