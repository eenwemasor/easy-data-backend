<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 20:21
 */

namespace App\Services;


use App\Enums\TransactionType;
use App\Events\TransferFundRequest;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\WithdrawFundRequestRegisterRepository;
use App\RequestRegister;
use App\User;
use App\UserBank;
use Carbon\Carbon;

class WithdrawFundRequestRegisterService
{
    /**
     * @var WithdrawFundRequestRegisterRepository
     */
    private $fundRequestRegisterRepository;
    /**
     * @var WithdrawFundTransactionService
     */
    private $withdrawFundTransactionService;

    /**
     * WithdrawFundRequestRegisterService constructor.
     * @param WithdrawFundRequestRegisterRepository $fundRequestRegisterRepository
     * @param WithdrawFundTransactionService $withdrawFundTransactionService
     */
    function __construct(WithdrawFundRequestRegisterRepository $fundRequestRegisterRepository, WithdrawFundTransactionService $withdrawFundTransactionService)
    {
        $this->fundRequestRegisterRepository = $fundRequestRegisterRepository;
        $this->withdrawFundTransactionService = $withdrawFundTransactionService;
    }

    public function create(array $withdrawFund)
    {
        $expiry_date = Carbon::now()->addDay();
        $otp = mt_rand(100000, 999999);


        $amount = $withdrawFund['amount'];

        $sender = User::find($withdrawFund['sender_id']);

        if (!$sender->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $target_bank = UserBank::find($withdrawFund['receiver_id']);

        if(isset($target_bank)){
            if($sender->transaction_pin == $withdrawFund['transaction_pin']){
                if($sender->wallet < $amount){
                    throw new GraphqlError("Insufficient Balance");
                }else{
                    $withdrawFund['otp'] = $otp;
                    $withdrawFund['expiry_date'] = $expiry_date;
                    $withdrawFund['receiver_id'] = $target_bank->id;

                    event(new TransferFundRequest($sender, $otp));
                    return  $this->fundRequestRegisterRepository->create($withdrawFund);
                }
            }else{
                throw new GraphqlError("Invalid Transaction pin");
            }

        }else{
            throw new GraphqlError("Bank does not exist ");
        }
    }


    public function initiate_transfer_fund( string $otp)
    {
        $transferRequest =  RequestRegister::where('otp',$otp)->first();

        $current_date = Carbon::now();


        if(isset($transferRequest)){
            $request_expiry_date = Carbon::create($transferRequest->expiry_date);
            if($request_expiry_date->lessThanOrEqualTo($current_date)){
                $request_register = RequestRegister::where('otp', $otp);
                $request_register->delete();
                throw new GraphqlError("One time password already expired");
            }else{

                $transaction_data = [
                    "transaction_type"=>TransactionType::DEBIT,
                    "description"=>"Fund Transfer",
                    "amount"=>$transferRequest->amount,
                    "bank_id"=>$transferRequest->receiver_id,
                    "user_id"=>$transferRequest->sender_id
                ];

                $this->withdrawFundTransactionService->create($transaction_data);

                $request_register = RequestRegister::where('otp', $otp);
                $request_register->delete();

                return $transferRequest;
            }

        }else{
            throw new GraphqlError("Invalid One time Password");
        }
        
    }

}