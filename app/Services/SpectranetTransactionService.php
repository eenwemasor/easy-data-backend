<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Repositories\SpectranetTransactionRepository;
use App\SpectranetPriceList;
use App\User;
use App\Vendors\MobileNg\MobileNgSmile;
use App\Vendors\MobileNg\MobileNgSpectranet;

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
        $smilePackage = SpectranetPriceList::find($args['plan']);
        $walletTransactionData = [
            'transaction_type' => TransactionType::DEBIT,
            'beneficiary' => $user->full_name,
            'description' => $smilePackage->description,
            'amount' => $smilePackage->price,
            'user_id' => $user->id,
        ];

        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $smileResponse = $this->mobileNgSpectranet->purchase_spectranet($smilePackage, $walletTransactionResult->reference, $args);
//        if (isset($smileResponse->description) && isset($smileResponse->code)) {
//            $user = User::find($args['user_id']);
//            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
//                $user->wallet = $user->wallet + $smilePackage->price;
//            } else {
//                $user->bonus_wallet = $user->bonus_wallet + $smilePackage->price;
//            }
//            $user->save();
//            $walletTransaction = WalletTransaction::find($walletTransactionResult->id);
//            $walletTransaction->status = TransactionStatus::FAILED;
//            $walletTransaction->save();
//
//            throw new GraphqlError("failed to process " . str_lower($smilePackage->description) . " at the moment, please try again later.");
//        } else {
//            $walletResult = collect($walletTransactionResult);
//            $airtimePrint['status'] = TransactionStatus::COMPLETED;
//            $airtimePrint['result_checker_id'] = $args['result_checker_id'];
//            $resultCheckTransaction = array_merge($walletResult->except(['transaction_type', 'description', 'status'])->toArray(), $airtimePrint);
//            $resultCheckTransaction = $this->resultCheckerRepository->create($resultCheckTransaction);
//
//            if ($smilePackage->examination_body === ResultCheckerExamBody::WAEC) {
//                $pins = $smileResponse->details->pins;
//                foreach ($pins as $pin) {
//                    ResultCheckerPin::create([
//                        'serial_number' => $pin->serial_number,
//                        'pin' => $pin->pin,
//                        'result_check_transaction_id' => $resultCheckTransaction->id
//                    ]);
//                }
//            } else {
//                $tokens = $smileResponse->details->tokens;
//                foreach ($tokens as $token) {
//                    ResultCheckerPin::create([
//                        'serial_number' => $resultCheckTransaction->id . "-" . strtoupper(Str::random(10)),
//                        'pin' => $token->token,
//                        'result_check_transaction_id' => $resultCheckTransaction->id
//                    ]);
//                }
//            }
//            return $resultCheckTransaction;
//        }

    }


}
