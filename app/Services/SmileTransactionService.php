<?php


namespace App\Services;


use App\Enums\SmileTransactionType;
use App\Enums\TransactionType;
use App\Repositories\SmileTransactionRepository;
use App\SmilePriceList;
use App\User;
use App\Vendors\MobileNg\MobileNgSmile;

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
            'beneficiary' =>$args['beneficiary_name'],
            'user_id' => $user->id,
        ];
        if($args['transaction_type'] === SmileTransactionType::BUNDLE){
            $smilePackage = SmilePriceList::find($args['plan']);
            $walletTransactionData['description'] = $smilePackage->description;
            $walletTransactionData['amount'] = $smilePackage->price;
        }else{
            $walletTransactionData['description'] = $args['amount']. "worth of smile data purchase";
            $walletTransactionData['amount'] =$args['amount'];
        }
        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $smileResponse = $this->mobileNgSmile->purchase_smile($smilePackage, $walletTransactionResult->reference, $args);
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
