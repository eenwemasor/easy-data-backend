<?php


namespace App\Services;


use App\BankAccount;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Gateways\Paystack;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\WithdrawalTransactionRepository;
use App\User;
use App\WalletTransaction;
use App\WithdrawalTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WithdrawalTransactionService
{
    private $withdrawalTransactionRepository;
    private $paystack;
    private $walletTransactionService;

    /**
     * WithdrawalTransactionService constructor.
     * @param WithdrawalTransactionRepository $withdrawalTransactionRepository
     * @param Paystack $paystack
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(
        WithdrawalTransactionRepository $withdrawalTransactionRepository,
        Paystack $paystack,
        WalletTransactionService $walletTransactionService
    )
    {

        $this->withdrawalTransactionRepository = $withdrawalTransactionRepository;
        $this->paystack = $paystack;
        $this->walletTransactionService = $walletTransactionService;
    }


    /**
     * @param array $args
     * @return WithdrawalTransaction
     * @throws GraphqlError
     */
    public function create(array $args): WithdrawalTransaction
    {
        $user = User::find($args['user_id']);
        $bank = $this->getBankAccount($args['bank_id']);

        if($args['transaction_pin']  !== $user->transaction_pin){
            throw new GraphqlError("Incorrect transaction pin");
        }

        if(!isset($bank->recipient_code)){
            $bank = $this->paystack->create_transfer_recipient($bank);
        }
        $amount = $this->paystack->apply_discount($args, $args['amount']);
        $walletTransactionResult = $this->chargeUser($user, $amount, $bank);
        $walletTransactionCollection = collect($walletTransactionResult);

        $transferRequest = $this->paystack->initiate_transfer($bank, $amount);
        $withdrawalTransactionData = $this->composeWithdrawalTransactionData($walletTransactionCollection, $transferRequest, $bank);
        return $this->withdrawalTransactionRepository->create($withdrawalTransactionData);
    }

    /**
     * @param $bank_id
     * @return BankAccount
     */
    private function getBankAccount($bank_id): BankAccount
    {
      return BankAccount::with('bank')->find($bank_id)->first();

    }


    /**
     * @param $amount
     * @param BankAccount $bankAccount
     * @return WalletTransaction
     * @throws GraphqlError
     */
    private function chargeUser(User $user,  $amount, BankAccount $bankAccount)
    {
        $walletTransactionData = [
            'transaction_type' => TransactionType::DEBIT,
            'user_id' => $user->id,
            'description' => "Transfer of ".$amount . " to ". $bankAccount->name,
            'amount' => $amount,
            'beneficiary' => sprintf("%s (%s)", $bankAccount->name, $bankAccount->bank_number)
        ];

        return $this->walletTransactionService->create($walletTransactionData);
    }

    /**
     * @param Collection $walletTransactionCollection
     * @param $transferRequest
     * @param BankAccount $bank
     * @return array
     */
    private function composeWithdrawalTransactionData(Collection $walletTransactionCollection,  $transferRequest, BankAccount $bank): array
    {
        $withdrawalTransactionData = array_merge(
            $walletTransactionCollection->except(['transaction_type', 'wallet', 'status'])->toArray(),
            [
                'transfer_code' => $transferRequest->transfer_code,
                'transfer_reference' => $transferRequest->reference,
                'transfer_id' => $transferRequest->id,
                'method' => $walletTransactionCollection['wallet'],
                'bank_id' => $bank->id,
            ]
        );
        if (($transferRequest->status === "success")) {
            ($withdrawalTransactionData['status'] = TransactionStatus::COMPLETED);
        } else {
            ($withdrawalTransactionData['status'] = TransactionStatus::PROCESSING);
        }
        return $withdrawalTransactionData;
    }

    /**
     * @param $transferData
     * @return string
     * @throws GraphqlError
     */
    public function handle_transfer_success($transferData)
    {
        $withdrawalTransaction = $this->getWithdrawalTransaction($transferData);

        if(isset($withdrawalTransaction)){
            return $withdrawalTransaction->update(['status'=>TransactionStatus::COMPLETED]);
        }else{
            throw new GraphqlError('transaction does not exist');
        }
    }


    /**
     * @param $transferData
     * @return string
     * @throws GraphqlError
     */
    public function handle_transfer_failed($transferData)
    {
        $withdrawalTransaction = $this->getWithdrawalTransaction($transferData);

        if(isset($withdrawalTransaction)){
            $withdrawalTransaction->update(['status'=>TransactionStatus::FAILED]);

            $this->refundUser($withdrawalTransaction);
            return $withdrawalTransaction;
        }else{
            throw new GraphqlError('transaction does not exist');
        }
    }


    /**
     * @param $transferData
     * @return string
     * @throws GraphqlError
     */
    public function handle_transfer_reversed($transferData)
    {
        $withdrawalTransaction = $this->getWithdrawalTransaction($transferData);

        if(isset($withdrawalTransaction) && $withdrawalTransaction->status !== TransactionStatus::FAILED){
            $withdrawalTransaction->update(['status'=>TransactionStatus::FAILED]);

            $this->refundUser($withdrawalTransaction);
            return $withdrawalTransaction;
        }else{
            throw new GraphqlError('Status updated previous!');
        }
    }

    /**
     * @param WithdrawalTransaction $withdrawalTransaction
     * @return WalletTransaction
     * @throws GraphqlError
     */
    private function refundUser( WithdrawalTransaction $withdrawalTransaction):WalletTransaction
    {
       $walletTransactionData = [
            'amount' => $withdrawalTransaction->amount,
            'transaction_type' => TransactionType::CREDIT,
            'description' => 'Refund for failed transfer transaction with the following reference ' . $withdrawalTransaction->reference,
            'user_id' => $withdrawalTransaction->user->id,
            'beneficiary' => $withdrawalTransaction->user->username

        ];
       return $this->walletTransactionService->create($walletTransactionData);
    }

    /**
     * @param $transferDatavoid
     * @return mixed
     */
    private function getWithdrawalTransaction($transferData)
    {
        return WithdrawalTransaction::with('user')
                                    ->where('transfer_reference', $transferData['reference'])
                                    ->where('transfer_code', $transferData['transfer_code'])->first();

    }
}
