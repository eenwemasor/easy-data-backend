<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02/04/2020
 * Time: 22:19
 */

namespace App\Services;

use App\AdminChannelUtil;
use App\AirtimeTransaction;
use App\CableTransaction;
use App\DataTransaction;
use App\ElectricityTransaction;
use App\Enums\TransactionType;
use App\Events\AccountStatementEvent;
use App\GraphQL\Errors\GraphqlError;
use App\User;
use App\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;


class AccountStatementService
{
    /**
     * @var WalletTransaction
     */
    private $walletTransaction;
    private $user_id;
    private $from_date;
    private $to_date;

    /**
     * AccountStatementService constructor.
     * @param WalletTransactionService $walletTransaction
     */
    function __construct(WalletTransactionService $walletTransaction)
    {
        $this->walletTransaction = $walletTransaction;
    }

    public function generate_user_account_statement(array $arguments)
    {
        $this->user_id = $arguments['user_id'];
        $this->from_date = $arguments['from_date'];
        $this->to_date = $arguments['to_date'];

        $user = User::find($this->user_id);


        if (!$user) {
            throw new GraphqlError("Account does not Exist");
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->transaction_statement_to_html());


        $now = Carbon::now();
        $day = $now->dayName;
        $month = $now->monthName;
        $user_name = preg_replace('/\s+/', '_', $user->full_name);
        $statement_name = $now->day . "_" . $day . "_" . $month . "_" . $now->year . "_" . $user_name . ".pdf";
        $pdf->setPaper('a4', 'portrait')->save($statement_name);

        $this->walletTransaction->create([
            'transaction_type' => TransactionType::DEBIT,
            'description' => "Account Statement Request Charge",
            'amount' => AdminChannelUtil::first()->statement_request_charge,
            'beneficiary' => "Subpay Communication",
            'user_id' => $user->id,
        ]);

        event(new AccountStatementEvent($user, $statement_name));

        return ['statement_link' => "URL", 'message' => "Account statement generated and sent to your mail"];

    }


    private function transaction_statement_to_html()
    {
        $result = ' <div style="">
                        <img src="https://subpay.com.ng/static/media/logo.369399ab.svg" style="width:100px;">
                        <h2 style="margin-top: -40px">Subpay Communications</h2>
                        <hr>
                        <div>
                             <h3>Hello,' . User::find($this->user_id)->full_name . '</h3>
                             <p style="text-align: left">
                             Below are your transactions  between ' . Carbon::parse($this->from_date)->format('g:i a l jS F Y') . ' to ' . Carbon::parse($this->to_date)->format('g:i a l jS F Y') . '
                            </p>
                        </div>
                         <hr>
                         <div>
                            ' . $this->convert_wallet_transaction_to_html() . '
                        </div>
                         <div>
                            ' . $this->convert_airtime_transaction_to_html() . '
                        </div>
                         <div>
                            ' . $this->convert_cable_transaction_to_html() . '
                        </div>
                         <div>
                            ' . $this->convert_data_transaction_to_html() . '
                        </div>
                         <div>
                            ' . $this->convert_electricity_transaction_to_html() . '
                        </div>
                        <br/>
                        <hr/>
                         <div style="text-align: right;font-size: 12px; margin-top: 150px">
                            <p>Signed: Subpay Communication</p>
                               <p style="margin-top: -10px">Date: ' . Carbon::now()->format('g:i a l jS F Y') . '</p>
                        </div>
                    </div>
';
        return $result;
    }

    private function convert_wallet_transaction_to_html()
    {
        if (WalletTransaction::where('user_id', $this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = WalletTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id', $this->user_id)->get();
            $result = ' <div>
              <h4 style="text-align: left;margin-bottom: -10px;margin-left: 10px">Wallet transactions</h4>
             <table style="width: 100%;padding: 10px;font-size: 10px;">

                <thead style = "background-color: #636b6f;color: white;text-transform: uppercase">
                <tr><th style="padding-top: 10px;padding-bottom: 10px">Date</th>
                <th>Reference</th>
                <th>Transaction type</th>
                <th>Amount</th>
                <th>Initial Balance</th>
                <th>New Balance</th>

                   <th>Beneficiary</th></tr>
            </thead>
            <tbody>
                  ';
            foreach ($wallet_transactions as $transaction) {
                $result .= '
            <tr>
            <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . Carbon::parse($transaction->created_at)->format('g:i a l jS F Y') . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . $transaction->reference . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->transaction_type . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px">' . $transaction->amount . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->initial_balance . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->new_balance . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->beneficiary . '</td></tr>';
            }
            $result .= '</tbody>
                       </table>
                       </div>';
            return $result;
        } else {
            return "<div></div>";
        }

    }


    private function convert_airtime_transaction_to_html()
    {
        if (AirtimeTransaction::where('user_id', $this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = AirtimeTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id', $this->user_id)->get();
            $result = '<div><h4 style="text-align: left;margin-bottom: -10px;margin-left: 10px">Airtime purchase transactions</h4> <table style="width: 100%;padding: 10px;font-size: 10px;">

            <thead style = "background-color: #636b6f;color: white;text-transform: uppercase">
                <tr><th style="padding-top: 10px;padding-bottom: 10px">Date</th>
                 <th>Reference</th>
                 <th>Amount</th>
                 <th>Initial Balance</th>
                 <th>New Balance</th>
                 <th>Network</th>
                 <th>Phone</th>
                 <th>Method</th>
                 <th>status</th>
                   </tr>
            </thead>
            <tbody>
                  ';
            foreach ($wallet_transactions as $transaction) {
                $result .= '
                <tr><td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . Carbon::parse($transaction->created_at)->format('g:i a l jS F Y') . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . $transaction->reference . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->amount . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->initial_balance . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->new_balance . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->network . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->phone . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->method . '</td>
                    <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->status . '</td>
                </tr>
       ';
            }
            $result .= '</tbody>
                       </table></div>';
            return $result;
        } else {
            return "<div></div>";
        }


    }

    private function convert_cable_transaction_to_html()
    {

        if (CableTransaction::where('user_id', $this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = CableTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id', $this->user_id)->get();
            $result = '<div><h4 style="text-align: left;margin-bottom: -10px;margin-left: 10px">Cable subscription transactions</h4> <table style="width: 100%;padding: 10px;font-size: 10px;">

            <thead style = "background-color: #636b6f;color: white;text-transform: uppercase">
            <tr><th style="padding-top: 10px;padding-bottom: 10px">Date</th>
             <th>Reference</th>
               <th>Amount</th>
                    <th>Initial Balance</th>
                    <th>New Balance</th>
               <th>Decoder</th>
               <th>Decoder Number</th>
               <th>Beneficiary</th>
               <th>Plan</th>
               <th>Method</th>
               <th>status</th>
               </tr>
                </thead>
                <tbody>
                  ';
            foreach ($wallet_transactions as $transaction) {
                $result .= '
            <tr><td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . Carbon::parse($transaction->created_at)->format('g:i a l jS F Y') . '</td>
            <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . $transaction->reference . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->amount . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->initial_balance . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->new_balance . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->decoder . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->decoder_number . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->beneficiary_name . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->plan . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->method . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->status . '</td>
            </tr>
       ';
            }
            $result .= '</tbody>
                       </table></div>';
            return $result;
        } else {
            return "<div></div>";
        }
    }

    private function convert_data_transaction_to_html()
    {
        if (DataTransaction::where('user_id', $this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = DataTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])
                ->where('user_id', $this->user_id)->get();
            $result = '
            <div>
                <h4 style="text-align: left;
                            margin-bottom: -10px;
                            margin-left: 10px"> Data subscription transactions</h4>
                <table style="width: 100%;padding: 10px;font-size: 10px;">
                <thead style = "background-color: #636b6f;color: white;text-transform: uppercase">
                    <tr><th style="padding-top: 10px;padding-bottom: 10px">Date</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Initial Balance</th>
                        <th>New Balance</th>
                        <th>Network</th>
                        <th>Data Plan</th>
                        <th>Beneficiary</th>
                        <th>Method</th>
                        <th>status</th>
                    </tr>
                </thead>
            <tbody>
                  ';
            foreach ($wallet_transactions as $transaction) {
                $result .= '
            <tr><td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . Carbon::parse($transaction->created_at)->format('g:i a l jS F Y') . '</td>
            <   td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . $transaction->reference . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->amount . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->initial_balance . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->new_balance . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->network . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->data . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->beneficiary . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->method . '</td>
               <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->status . '</td>
            </tr>
       ';
            }
            $result .= '</tbody>
                       </table></div>';
            return $result;
        } else {
            return '<div></div>';
        }

    }

    private function convert_electricity_transaction_to_html()
    {
        if (ElectricityTransaction::where('user_id', $this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = ElectricityTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id', $this->user_id)->get();
            $result = '<div><h4 style="text-align: left;margin-bottom: -10px;margin-left: 10px">Electricity subscription transactions</h4> <table style="width: 100%;padding: 10px;font-size: 10px;">

            <thead style = "background-color: #636b6f;color: white;text-transform: uppercase">
                <tr><th style="padding-top: 10px;padding-bottom: 10px">Date</th>
                     <th>Reference</th>
                     <th>Amount</th>
                     <th>Initial Balance</th>
                     <th>New Balance</th>
                     <th>Meter Number</th>
                     <th>Beneficiary</th>
                     <th>Plan</th>
                     <th>Method</th>
                     <th>status</th>
                 </tr>
            </thead>
            <tbody>
                  ';
            foreach ($wallet_transactions as $transaction) {
                $result .= '
                <tr>
                   <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . Carbon::parse($transaction->created_at)->format('g:i a l jS F Y') . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . $transaction->reference . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->amount . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->initial_balance . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->new_balance . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->meter_number . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->beneficiary_name . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->plan . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->method . '</td>
                   <td style="padding-top: 2px;padding-bottom: 2px;">' . $transaction->status . '</td>
                </tr>
       ';
            }
            $result .= '</tbody>
                       </table></div>';
            return $result;
        } else {
            return "<div></div>";
        }

    }


}
