<?php


namespace App\Services;


use App\BankAccount;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WithdrawalDestination;
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
        $user = User::with('account_level')->find($args['user_id']);
        $amount = $this->paystack->apply_discount($args, $args['amount']);
        if ($args['transaction_pin'] !== $user->transaction_pin) {
            throw new GraphqlError("Incorrect transaction pin");
        }
        if (!$user->canWithdrawBonusWallet()) {
            throw new GraphqlError(
                "Error: can not withdraw bonus at the moment, to be eligible bonus wallet must exceed " .
                $user->account_level->bonus_wallet_withdrawal_minimum_balance);
        }

        $walletTransactionResult = $this->charge_user($args, $amount);
        $walletTransactionCollection = collect($walletTransactionResult);

        $withdrawalOption = null;
        if ($args['destination'] === WithdrawalDestination::BANK_ACCOUNT) {
            $withdrawalOption = $this->create_bank_account_withdrawal($args['bank_id'], $args['amount']);
        } else {
            $withdrawalOption = $this->credit_user_wallet($user, $args['amount']);
        }


        $withdrawalTransactionData = $this->compose_withdrawal_transaction_data($walletTransactionCollection, $withdrawalOption);
        return $this->withdrawalTransactionRepository->create($withdrawalTransactionData);
    }


    /**
     * @param array $args
     * @param $amount
     * @return WalletTransaction
     * @throws GraphqlError
     */
    private function charge_user(array $args, $amount): WalletTransaction
    {
        $user = User::find($args['user_id']);
        $beneficiary = "";
        $description = "";

        if ($args['destination'] === WithdrawalDestination::BANK_ACCOUNT) {
            $bankAccount = $this->get_bank_account($args['bank_id']);
            $beneficiary = sprintf("%s (%s)", $bankAccount->name, $bankAccount->bank_number);
            $description = "Transfer of " . $amount . " to " . $bankAccount->name;
        } else {
            $bankAccount = "Self";
            $description = "Transfer of " . $amount . " from bonus wallet to wallet";
        }
        $walletTransactionData = [
            'transaction_type' => TransactionType::DEBIT,
            'user_id' => $user->id,
            'description' => $description,
            'amount' => $amount,
            'beneficiary' => $beneficiary
        ];

        return $this->walletTransactionService->create($walletTransactionData, true);
    }

    /**
     * @param $bank_id
     * @return BankAccount
     */
    private function get_bank_account($bank_id): BankAccount
    {
        return BankAccount::with('bank')->find($bank_id)->first();

    }

    /**
     * @param $bank_id
     * @param float $amount
     * @return array
     * @throws GraphqlError
     */
    private function create_bank_account_withdrawal($bank_id, float $amount): array
    {
        $bank = $this->get_bank_account($bank_id);
        if (!isset($bank->recipient_code)) {
            $bank = $this->paystack->create_transfer_recipient($bank);
        }
        $transferRequest = $this->paystack->initiate_transfer($bank, $amount);
        return [
            'transfer_code' => $transferRequest->transfer_code,
            'transfer_reference' => $transferRequest->reference,
            'transfer_id' => $transferRequest->id,
            'bank_id' => $bank->id,
            'status' => $transferRequest->status
        ];
    }

    /**
     * @param $user
     * @param float $amount
     * @return string[]
     * @throws GraphqlError
     */
    private function credit_user_wallet( $user, float $amount): array
    {

        $this->walletTransactionService->create([
            'transaction_type' => TransactionType::CREDIT,
            'user_id' => $user->id,
            'description' => "bonus wallet withdrawal",
            'amount' => $amount,
            'beneficiary' => 'Self'
        ]);
        return ['status' => 'success'];
    }

    /**
     * @param Collection $walletTransactionCollection
     * @param array $withdrawalOption
     * @return array
     */
    private function compose_withdrawal_transaction_data(Collection $walletTransactionCollection, array $withdrawalOption): array
    {
        $withdrawalTransactionData = array_merge(
            $walletTransactionCollection->except(['transaction_type', 'wallet', 'status'])->toArray(),
            [
                'method' => $walletTransactionCollection['wallet'],

            ],
            collect($withdrawalOption)->except(['status'])->toArray()
        );
        if (($withdrawalOption['status'] === "success")) {
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
        $withdrawalTransaction = $this->get_withdrawal_transaction($transferData);

        if (isset($withdrawalTransaction)) {
            return $withdrawalTransaction->update(['status' => TransactionStatus::COMPLETED]);
        } else {
            throw new GraphqlError('transaction does not exist');
        }
    }

    /**
     * @param $transferDatavoid
     * @return mixed
     */
    private function get_withdrawal_transaction($transferData)
    {
        return WithdrawalTransaction::with('user')
            ->where('transfer_reference', $transferData['reference'])
            ->where('transfer_code', $transferData['transfer_code'])->first();

    }

    /**
     * @param $transferData
     * @return string
     * @throws GraphqlError
     */
    public function handle_transfer_failed($transferData)
    {
        $withdrawalTransaction = $this->get_withdrawal_transaction($transferData);

        if (isset($withdrawalTransaction)) {
            $withdrawalTransaction->update(['status' => TransactionStatus::FAILED]);

            $this->refund_user($withdrawalTransaction);
            return $withdrawalTransaction;
        } else {
            throw new GraphqlError('transaction does not exist');
        }
    }

    /**
     * @param WithdrawalTransaction $withdrawalTransaction
     * @return WalletTransaction
     * @throws GraphqlError
     */
    private function refund_user(WithdrawalTransaction $withdrawalTransaction): WalletTransaction
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
     * @param $transferData
     * @return string
     * @throws GraphqlError
     */
    public function handle_transfer_reversed($transferData)
    {
        $withdrawalTransaction = $this->get_withdrawal_transaction($transferData);

        if (isset($withdrawalTransaction) && $withdrawalTransaction->status !== TransactionStatus::FAILED) {
            $withdrawalTransaction->update(['status' => TransactionStatus::FAILED]);

            $this->refund_user($withdrawalTransaction);
            return $withdrawalTransaction;
        } else {
            throw new GraphqlError('Status updated previous!');
        }
    }
}
