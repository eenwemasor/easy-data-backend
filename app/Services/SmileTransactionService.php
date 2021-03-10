<?php


namespace App\Services;


use App\Enums\SmileTransactionType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\SmileTransactionRepository;
use App\SmilePriceList;
use App\User;
use App\Vendors\MobileNg\MobileNgSmile;
use App\WalletTransaction;

class SmileTransactionService
{

    /**
     * @var SmileTransactionRepository
     */
    private $smileTransactionRepository;

    /**
     * @var MobileNgSmile
     */
    private $mobileNgSmile;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * SmileTransactionService constructor.
     * @param SmileTransactionRepository $smileTransactionRepository
     * @param MobileNgSmile $mobileNgSmile
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(
        SmileTransactionRepository $smileTransactionRepository,
        MobileNgSmile $mobileNgSmile,
        WalletTransactionService $walletTransactionService
    )
    {
        $this->smileTransactionRepository = $smileTransactionRepository;
        $this->mobileNgSmile = $mobileNgSmile;
        $this->walletTransactionService = $walletTransactionService;
    }

    /**
     * @param array $args
     * @throws \App\GraphQL\Errors\GraphqlError
     */
    public function create(array $args)
    {
        $user = User::find($args['user_id']);
        $smilePackage = null;
        $walletTransactionData = [
            'transaction_type' => TransactionType::DEBIT,
            'beneficiary' => $args['beneficiary_name'],
            'user_id' => $user->id,
        ];
        $amount = null;
        if ($args['transaction_type'] === SmileTransactionType::BUNDLE) {
            $smilePackage = SmilePriceList::find($args['plan']);
            $amount = $this->mobileNgSmile->apply_discount($args, $smilePackage->price);
            $walletTransactionData['description'] = $smilePackage->description;
            $walletTransactionData['amount'] = $amount;
        } else {
            $amount = $this->mobileNgSmile->apply_discount($args, $args['amount']);
            $walletTransactionData['description'] = $args['description'];
            $walletTransactionData['amount'] = $amount;
        }
        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $smileResponse = $this->mobileNgSmile->purchase_smile($smilePackage, $walletTransactionResult->reference, $args);

        $walletResult = collect($walletTransactionResult);
        $smileTransactionData = [
            'smart_card_number' => $args['smart_card_number'],
            'beneficiary_name' => $args['beneficiary_name'],
            'method' => $walletTransactionResult->wallet,
            'plan_id' => isset($smilePackage->id) ? $smilePackage->id: null
        ];
        $smileTransaction = array_merge($walletResult->except(['transaction_type', 'description', 'status','wallet'])->toArray(), $smileTransactionData);

        if ($smileResponse['success']) {
            $smileTransaction['status'] = TransactionStatus::COMPLETED;
            $smileTransaction = $this->smileTransactionRepository->create($smileTransaction);

            return $smileTransaction;
        } else {
            $smileTransaction['status'] = TransactionStatus::FAILED;
            $this->smileTransactionRepository->create($smileTransaction);

            $user = User::find($args["user_id"]);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $amount;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $amount;
            }
            $user->save();

            $walletTransaction = WalletTransaction::find($walletTransactionResult['id']);
            $walletTransaction->status = TransactionStatus::FAILED;
            $walletTransaction->save();

            throw new GraphqlError($smileResponse['message']);
        }

    }


}
