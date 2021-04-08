<?php

namespace App\Http\Controllers;

use App\Services\WithdrawalTransactionService;
use Illuminate\Http\Request;

class PaystackController extends Controller
{
    private $withdrawalTransactionService;

    /**
     * PaystackController constructor.
     * @param WithdrawalTransactionService $withdrawalTransactionService
     */
    public function __construct(WithdrawalTransactionService $withdrawalTransactionService)
    {
        $this->withdrawalTransactionService = $withdrawalTransactionService;
    }

    public function index(Request $request)
    {
        switch ($request->event){
            case "transfer.success":{
                return $this->withdrawalTransactionService->handle_transfer_success($request->data);
            }
            case "transfer.failed":{
                return  $this->withdrawalTransactionService->handle_transfer_failed($request->data);
            }
            case "transfer.reversed": {
                return $this->withdrawalTransactionService->handle_transfer_reversed($request->data);
            }
            default: return "unprocessed";
        }
    }
}
