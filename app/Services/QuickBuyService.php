<?php


namespace App\Services;


use App\AdminChannelUtil;
use App\CablePlanList;
use App\DataPlanList;
use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use App\Events\QuickBuyEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\UserController;
use App\PowerPlanList;
use App\Repositories\APIRequests\AirtimeAPIRequests;
use App\Repositories\APIRequests\CableAPIRequests;
use App\Repositories\APIRequests\ElectricityAPIRequests;
use App\Repositories\APIRequests\ValidateTransactions;
use App\Repositories\QuickBuyRepository;

class QuickBuyService
{
    /**
     * @var QuickBuyRepository
     */
    private $quick_buy_repository;
    /**
     * @var ValidateTransactions
     */
    private $validateTransactions;
    /**
     * @var AirtimeAPIRequests
     */
    private $airtimeAPIRequests;
    /**
     * @var CableAPIRequests
     */
    private $cableAPIRequests;
    /**
     * @var ElectricityAPIRequests
     */
    private $electricityAPIRequests;

    /**
     * QuickBuyService constructor.
     * @param QuickBuyRepository $quick_buy_repository
     * @param ValidateTransactions $validateTransactions
     * @param AirtimeAPIRequests $airtimeAPIRequests
     * @param CableAPIRequests $cableAPIRequests
     * @param ElectricityAPIRequests $electricityAPIRequests
     */
    public function __construct(QuickBuyRepository $quick_buy_repository, ValidateTransactions $validateTransactions, AirtimeAPIRequests $airtimeAPIRequests, CableAPIRequests $cableAPIRequests, ElectricityAPIRequests $electricityAPIRequests)
    {
        $this->quick_buy_repository = $quick_buy_repository;
        $this->validateTransactions = $validateTransactions;
        $this->airtimeAPIRequests = $airtimeAPIRequests;
        $this->cableAPIRequests = $cableAPIRequests;
        $this->electricityAPIRequests = $electricityAPIRequests;
    }


    /**
     * @param array $quickBuy
     * @return \App\QuickBuy
     * @throws
     */
    public function process_airtime_quick_buy(array $quickBuy)
    {
        $api_wallet = $this->validateTransactions->get_api_account_info();
        if($api_wallet->balance < $quickBuy['amount']){
            throw new GraphqlError("Service is not available currently, please try again later");
        }

        $initiateTransaction = $this->airtimeAPIRequests->initiate_airtime_transaction(['amount' => $quickBuy['amount'], 'request_id' => $quickBuy['reference'], 'msisdn' => $quickBuy['phone'],]);


        if ($initiateTransaction->message == "SUCCESSFUL" && $initiateTransaction->status == "200") {

            $user_cont = New UserController();
            $quickBuy['status'] = TransactionStatus::COMPLETED;
            $quick_buy = $this->quick_buy_repository->create($quickBuy);
            $user = $quickBuy["email"];
            $admin = $user_cont->getAdmin();
            event(new QuickBuyEvent($quick_buy, $user, $admin));

            return $quick_buy;


        } else {
            $user_cont = New UserController();
            $quickBuy['status'] = TransactionStatus::FAILED;
            $quick_buy = $this->quick_buy_repository->create($quickBuy);
            $user = $quickBuy["email"];
            $admin = $user_cont->getAdmin();
            event(new QuickBuyEvent($quick_buy, $user, $admin));

            throw new GraphqlError($initiateTransaction->message);

        }


    }


    /**
     * @param array $quickBuy
     * @return \App\QuickBuy
     * @throws
     */
    public function process_data_quick_buy(array $quickBuy)
    {
        $data_plan = DataPlanList::find($quickBuy['data']);
        $phone_details = $this->validateTransactions->get_phone_vendor_details($quickBuy['beneficiary'])->opts;
        if (strtoupper($phone_details->operator) != $data_plan->network) {
            throw new GraphqlError("Please ensure phone number provided belongs to the network selected");
        }

        $sendSMS = new SendSMSController();
        $admin_utils = AdminChannelUtil::all()->first();


        $message = null;

        switch ($data_plan->network) {
            case NetworkType::MTN: {
            $message = $data_plan->product_code . " " . $quickBuy['beneficiary'] . " " . $data_plan->vendor_amount . " " . $admin_utils->data_pin;
            break;
        }
            case NetworkType::GLO: {
                $message = $data_plan->product_code . " " . $quickBuy['beneficiary'] . "#";
                break;
            }
            case NetworkType::NINE_MOBILE: {
                $message = $data_plan->product_code . " " . $quickBuy['beneficiary'] . "#";
                break;
            }
            case NetworkType::AIRTEL: {
                $message = $data_plan->product_code . " " . $quickBuy['beneficiary'] . "*" . $admin_utils->data_pin . "#";
                break;
            }
            default: {
                throw new GraphqlError("Invalid Network Value");
            }
        }
        $sendSMS->sendSMS($message);

        $user_cont = New UserController();
        $quickBuy['status'] = TransactionStatus::PROCESSING;
        $quickBuy['amount'] = $data_plan->amount;
        $quickBuy['network'] = $data_plan->network;
        $quickBuy['data'] = $data_plan->plan;
        $quick_buy = $this->quick_buy_repository->create($quickBuy);
        $user = $quickBuy["email"];
        $admin = $user_cont->getAdmin();
        event(new QuickBuyEvent($quick_buy, $user, $admin));

        return $quick_buy;
    }

    /**
     * @param array $quickBuy
     * @return \App\QuickBuy
     * @throws
     */
    public function process_bill_quick_buy(array $quickBuy)
    {
        $cable_plan = CablePlanList::find($quickBuy['plan']);

        $api_wallet = $this->validateTransactions->get_api_account_info();
        if($api_wallet->balance < $cable_plan->amount){
            throw new GraphqlError("Service is not available currently, please try again later");
        }

        $available_services = $this->validateTransactions->get_available_services("Tv");
        CableTransactionService::checkAvailableService($available_services, $cable_plan->cable);


        $initiate_cable_transaction = $this->cableAPIRequests->initiate_cable_transaction(['type' => $cable_plan->cable, 'smartCardNo' => $quickBuy['decoder_number'], 'name' => $cable_plan->plan, 'code' => $cable_plan->product_code, 'request_id' => $quickBuy['reference'],

        ], $cable_plan->amount);


        if ($initiate_cable_transaction->message == "SUCCESSFUL" && $initiate_cable_transaction->status == "200") {
            $user_cont = New UserController();
            $quickBuy['amount'] = $cable_plan->amount;
            $quickBuy['decoder'] = $cable_plan->decoder;
            $quickBuy['plan'] = $cable_plan->plan;
            $quickBuy['status'] = TransactionStatus::COMPLETED;
            $quick_buy = $this->quick_buy_repository->create($quickBuy);
            $user = $quickBuy["email"];
            $admin = $user_cont->getAdmin();
            event(new QuickBuyEvent($quick_buy, $user, $admin));

            return $quick_buy;
        } else {
            $user_cont = New UserController();
            $quickBuy['amount'] = $cable_plan->amount;
            $quickBuy['decoder'] = $cable_plan->decoder;
            $quickBuy['plan'] = $cable_plan->plan;
            $quickBuy['status'] = TransactionStatus::FAILED;
            $quick_buy = $this->quick_buy_repository->create($quickBuy);
            $user = $quickBuy["email"];
            $admin = $user_cont->getAdmin();
            event(new QuickBuyEvent($quick_buy, $user, $admin));


            return $quick_buy;
        }


    }


    /**
     * @param array $quickBuy
     * @return \App\QuickBuy
     * @throws
     */
    public function process_power_quick_buy(array $quickBuy)
    {
        $plan = PowerPlanList::find($quickBuy['plan']);
        $available_services = $this->validateTransactions->get_available_services('ELECT');
        $this->checkAvailableService($available_services, $plan->disco);

        $api_wallet = $this->validateTransactions->get_api_account_info();
        if($api_wallet->balance < $quickBuy['amount']){
            throw new GraphqlError("Service is not available currently, please try again later");
        }


        $initiate_electricity_transaction = $this->electricityAPIRequests->initiate_electricity_transaction(["disco" => $plan->disco, "meterNo" => $quickBuy['meter_number'], "type" => $quickBuy['electricity_type'], "amount" => $quickBuy['amount'], "phonenumber" => $quickBuy['phone'], "request_id" => $quickBuy['reference']

        ], $quickBuy['beneficiary_name']);


        if ($initiate_electricity_transaction->message == "SUCCESSFUL" && $initiate_electricity_transaction->status == "200") {
            $user_cont = New UserController();
            $quickBuy['status'] = TransactionStatus::COMPLETED;
            $quick_buy = $this->quick_buy_repository->create($quickBuy);
            $user = $quickBuy["email"];
            $admin = $user_cont->getAdmin();
            event(new QuickBuyEvent($quick_buy, $user, $admin));
            return $quick_buy;
        } else {
            $user_cont = New UserController();
            $quickBuy['transaction_type'] = $plan->disco . " " . $plan->description."Transaction";
            $quickBuy['status'] = TransactionStatus::FAILED;
            $quick_buy = $this->quick_buy_repository->create($quickBuy);
            $user = $quickBuy["email"];
            $admin = $user_cont->getAdmin();
            event(new QuickBuyEvent($quick_buy, $user, $admin));
            return $quick_buy;

        }

    }

    public function checkAvailableService($services, $service)
    {
        if ((!isset($services->Abuja) || $services->Abuja != "Available") && $service == "AEDC") {
            throw new GraphqlError("Abuja electricity service is currently not available");
        }
        if ((!isset($services->Eko) || $services->Eko != "Available") && $service == "EKEDC") {
            throw new GraphqlError("Eko electricity service is currently not available");
        }
        if ((!isset($services->Kaduna) || $services->Kaduna != "Available") && "KAEDC") {
            throw new GraphqlError("Kaduna electricity service is currently not available");
        }
        if ((!isset($services->Ibadan) || $services->Ibadan != "Available") && $service == "IBEDC") {
            throw new GraphqlError("Ibadan electricity service is currently not available");
        }
        if ((!isset($services->Kano) || $services->Kano != "Available") && $service == "KEDC") {
            throw new GraphqlError("Kano electricity service is currently not available");
        }
        if ((!isset($services->Ikeja) || $services->Ikeja != "Available") && $service == "IKEDC") {
            throw new GraphqlError("Ikeja electricity service is currently not available");
        }
        if ((!isset($services->portharcourt) || $services->portharcourt != "Available") && $service == "PHEDC") {
            throw new GraphqlError("Portharcourt electricity service is currently not available");
        }
        if ((!isset($services->Enugu) || $services->Enugu != "Available") && $service == "EEDC") {
            throw new GraphqlError("Enugu electricity service is currently not available");
        }
        if ((!isset($services->Jos) || $services->Jos != "Available") && $service == "JEDC") {
            throw new GraphqlError("Jos electricity service is currently not available");
        }
        if ((!isset($services->Benin) || $services->Benin != "Available") && $service == "BEDC") {
            throw new GraphqlError("Benin electricity service is currently not available");
        }
        if ((!isset($services->Yola) || $services->Yola != "Available") && $service == "YEDC") {
            throw new GraphqlError("Yola electricity service is currently not available");
        }
    }

    /**
     * @param string $phone
     * @param string $network
     * @return bool
     * @throws
     */
    public function verify_quick_buy_transaction(string $phone, string $network)
    {
        $phone_details = $this->validateTransactions->get_phone_vendor_details($phone)->opts;
        if (strtoupper($phone_details->operator) != $network) {
            throw new GraphqlError("Please ensure phone number provided belongs to the network selected");
        }

        return true;
    }


}