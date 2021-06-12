<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\SpectranetTransactionRepository;
use App\SpectranetPriceList;
use App\SpectranetTransactionPin;
use App\User;
use App\Vendors\MobileNg\MobileNgSmile;
use App\Vendors\MobileNg\MobileNgSpectranet;
use App\WalletTransaction;

class SpectranetTransactionService
{

    /**
     * @var SpectranetTransactionRepository
     */
    private $spectranetTransactionRepository;


    /**
     * @var MobileNgSpectranet
     */
    private $mobileNgSpectranet;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * SpectranetTransactionService constructor.
     * @param SpectranetTransactionRepository $spectranetTransactionRepository
     * @param MobileNgSpectranet $mobileNgSpectranet
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(
        SpectranetTransactionRepository $spectranetTransactionRepository,
        MobileNgSpectranet $mobileNgSpectranet,
        WalletTransactionService $walletTransactionService
    )
    {
        $this->spectranetTransactionRepository = $spectranetTransactionRepository;
        $this->mobileNgSpectranet = $mobileNgSpectranet;
        $this->walletTransactionService = $walletTransactionService;
    }

    /**
     * @param array $args
     * @throws \App\GraphQL\Errors\GraphqlError
     */
    public function create(array $args)
    {
        $user = User::find($args['user_id']);
        $spectranetPackage = SpectranetPriceList::find($args['plan']);
        $amount = $this->mobileNgSpectranet->apply_discount($args, $spectranetPackage->price);
        $walletTransactionResult = $this->chargeUser($user, $spectranetPackage,$amount);

        $spectranetResponse = $this->mobileNgSpectranet->purchase_spectranet($spectranetPackage, $walletTransactionResult->reference, $args);

        $walletResult = collect($walletTransactionResult);
        $spectranetTransactionData = [
            'method' => $walletTransactionResult->wallet,
            'plan_id' => isset($spectranetPackage->id) ? $spectranetPackage->id: null
        ];
        $spectranetTransaction = array_merge($walletResult->except(['transaction_type', 'description', 'status','wallet'])->toArray(), $spectranetTransactionData);

        if ($spectranetResponse['success']) {
            $spectranetTransactionData['status'] = TransactionStatus::COMPLETED;
            $spectranetTransaction = $this->spectranetTransactionRepository->create($spectranetTransaction);

            SpectranetTransactionPin::create([
                'serial_number'=>$spectranetResponse['pin']->serial_number,
                'pin'=> $spectranetResponse['pin']->pin,
                'value'=>$spectranetResponse['pin']->value,
                'spectranet_transaction_id'=>$spectranetTransaction->id
            ]);

            return $spectranetTransaction;
        } else {
            $spectranetTransaction['status'] = TransactionStatus::FAILED;
            $this->spectranetTransactionRepository->create($spectranetTransaction);

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

            throw new GraphqlError($spectranetResponse['message']);
        }

    }

    /**
     * @param $user
     * @param $smilePackage
     * @param $amount
     * @return mixed
     * @throws \App\GraphQL\Errors\GraphqlError
     */
    private function chargeUser($user, $smilePackage, $amount)
    {
        $walletTransactionData = [
            'transaction_type' => TransactionType::DEBIT,
            'beneficiary' => $user->full_name,
            'description' => $smilePackage->description,
            'amount' => $amount,
            'user_id' => $user->id,
        ];

       return  $this->walletTransactionService->create($walletTransactionData);
      ;
    }


}
