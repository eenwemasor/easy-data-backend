<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 14:11
 */

namespace App\Services;


use App\Enums\TransactionType;
use App\Events\TransferFundRequest;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\TransferFundRequestRegisterRepository;
use App\RequestRegister;
use App\TransferFundTransaction;
use App\User;
use Carbon\Carbon;
use Nuwave\Lighthouse\Schema\Types\Scalars\Date;

class TransferFundRequestRegisterService
{
    /**
     * @var TransferFundRequestRegisterRepository
     */
    private $transferFundRequestRegisterRepository;
    /**
     * @var TransferFundTransactionService
     */
    private $transferFundTransactionService;

    /**
     * TransferFundRequestRegisterService constructor.
     * @param TransferFundRequestRegisterRepository $transferFundRequestRegisterRepository
     * @param TransferFundTransactionService $transferFundTransactionService
     */
    function __construct(TransferFundRequestRegisterRepository $transferFundRequestRegisterRepository, TransferFundTransactionService $transferFundTransactionService)
    {
        $this->transferFundRequestRegisterRepository = $transferFundRequestRegisterRepository;
        $this->transferFundTransactionService = $transferFundTransactionService;
    }


    public function create(array $transferFundRegister)
    {
        $expiry_date = Carbon::now()->addDay();
        $otp = mt_rand(100000, 999999);


        $amount = $transferFundRegister['amount'];

        $sender = User::find($transferFundRegister['sender_id']);

        if (!$sender->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }



        $receiver = User::where('email', $transferFundRegister['receiver_id'])->orWhere('phone', $transferFundRegister['receiver_id'])->first();

        if(isset($receiver)){
            if($sender->transaction_pin == $transferFundRegister['transaction_pin']){
                if($sender->wallet < $amount){
                    throw new GraphqlError("Insufficient Balance");
                }else{
                    $transferFundRegister['otp'] = $otp;
                    $transferFundRegister['expiry_date'] = $expiry_date;
                    $transferFundRegister['receiver_id'] = $receiver->id;

                    event(new TransferFundRequest($sender, $otp));
                    return  $this->transferFundRequestRegisterRepository->create($transferFundRegister);
                }
            }else{
                throw new GraphqlError("Invalid Transaction pin");
            }

        }else{
            throw new GraphqlError("User with email or phone does not exist");
        }

    }

    /**
     * @param string $otp
     * @return mixed
     * @throws
     */
    public function initiate_transfer_fund(string $otp)
    {
       $transferRequest =  RequestRegister::where('otp',$otp)->first();
        $current_date = Carbon::now();


        if(isset($transferRequest)){
            $request_expiry_date = Carbon::create($transferRequest->expiry_date);
            if($request_expiry_date->lessThanOrEqualTo($current_date)){
                throw new GraphqlError("One time password already expired");
            }else{

                $transaction_data = [
                    "transaction_type"=>TransactionType::DEBIT,
                    "description"=>"Fund Transfer",
                    "amount"=>$transferRequest->amount,
                    "recipient_id"=>$transferRequest->receiver_id,
                    "user_id"=>$transferRequest->sender_id
                ];

               $this->transferFundTransactionService->create($transaction_data);

                $request_register = RequestRegister::where('otp', $otp);
                $request_register->delete();
                return $transferRequest;
            }

        }else{
            throw new GraphqlError("Invalid One time Password");
        }

    }

}