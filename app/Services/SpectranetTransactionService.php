<?php


namespace App\Services;


use App\Enums\ResultCheckerExamBody;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\ResultCheckerRepository;
use App\ResultChecker;
use App\ResultCheckerPin;
use App\User;
use App\Vendors\MobileNg\MobileNgResultChecker;
use App\WalletTransaction;
use Illuminate\Support\Str;

class SpectranetTransactionService
{
    /**
     * @var ResultCheckerRepository
     */
    private $resultCheckerRepository;
    /**
     * @var MobileNgTransaction
     */
    private $mobileNgResultChecker;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * ResultCheckerService constructor.
     * @param ResultCheckerRepository $resultCheckerRepository
     * @param MobileNgResultChecker $mobileNgResultChecker
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(
        ResultCheckerRepository $resultCheckerRepository,
        MobileNgResultChecker $mobileNgResultChecker,
        WalletTransactionService $walletTransactionService
    )
    {
        $this->resultCheckerRepository = $resultCheckerRepository;
        $this->mobileNgResultChecker = $mobileNgResultChecker;
        $this->walletTransactionService = $walletTransactionService;
    }

    /**
     * @param array $args
     * @throws \App\GraphQL\Errors\GraphqlError
     */
    public function create(array $args)
    {
        $resultCheckerPackage = ResultChecker::find($args['result_checker_id']);
        $user = User::find($args['user_id']);

        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $walletTransactionData = [
            'transaction_type' => TransactionType::DEBIT,
            'description' => $resultCheckerPackage->description,
            'amount' => $resultCheckerPackage->price,
            'beneficiary' => $user->full_name,
            'user_id' => $user->id
        ];
        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
       $resultCheckerResponse =  $this->mobileNgResultChecker->purchase_result_checker($resultCheckerPackage, $walletTransactionResult->reference);
        if (isset($resultCheckerResponse->description) && isset($resultCheckerResponse->code)) {
            $user = User::find($args['user_id']);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $resultCheckerPackage->price;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $resultCheckerPackage->price;
            }
            $user->save();
            $walletTransaction = WalletTransaction::find($walletTransactionResult->id);
            $walletTransaction->status = TransactionStatus::FAILED;
            $walletTransaction->save();

            throw new GraphqlError("failed to process ".str_lower( $resultCheckerPackage->description)." at the moment, please try again later.");
        }else{
            $walletResult = collect($walletTransactionResult);
            $airtimePrint['status'] = TransactionStatus::COMPLETED;
            $airtimePrint['result_checker_id'] = $args['result_checker_id'];
            $resultCheckTransaction = array_merge($walletResult->except(['transaction_type', 'description', 'status'])->toArray(), $airtimePrint);
            $resultCheckTransaction = $this->resultCheckerRepository->create($resultCheckTransaction);

            if($resultCheckerPackage->examination_body === ResultCheckerExamBody::WAEC) {
                $pins = $resultCheckerResponse->details->pins;
                foreach ($pins as $pin) {
                    ResultCheckerPin::create([
                        'serial_number' => $pin->serial_number,
                        'pin' => $pin->pin,
                        'result_check_transaction_id' => $resultCheckTransaction->id
                    ]);
                }
            } else{
                $tokens = $resultCheckerResponse->details->tokens;
                foreach ($tokens as $token) {
                    ResultCheckerPin::create([
                        'serial_number' => $resultCheckTransaction->id."-". strtoupper(Str::random(10)),
                        'pin' => $token->token,
                        'result_check_transaction_id' => $resultCheckTransaction->id
                    ]);
                }
            }
            return $resultCheckTransaction;
        }

    }


}
