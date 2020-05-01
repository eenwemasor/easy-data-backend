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

    /**
     * AccountStatementService constructor.
     * @param WalletTransactionService $walletTransaction
     */
    function __construct(WalletTransactionService $walletTransaction)
    {
        $this->walletTransaction = $walletTransaction;
    }

    private $user_id;
    private $from_date;
    private $to_date;

    public function generate_user_account_statement(array $arguments)
    {
        $this->user_id = $arguments['user_id'];
        $this->from_date = $arguments['from_date'];
        $this->to_date = $arguments['to_date'];

        $user = User::find($this->user_id);

        if(!$user->active){
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }


        if(!$user){
            throw new GraphqlError("Account does not Exist");
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->transaction_statement_to_html());


        $now = Carbon::now();
        $day = $now->dayName;
        $month = $now->monthName;
        $user_name = preg_replace('/\s+/', '_', $user->full_name);
        $statement_name = $now->day."_".$day."_".$month."_".$now->year."_".$user_name.".pdf";
        $pdf->setPaper('a4', 'portrait')->save($statement_name);

        $this->walletTransaction->create([
            'transaction_type' => TransactionType::DEBIT,
            'description' =>"Account Statement Request Charge",
            'amount' => AdminChannelUtil::all()->first()->statement_request_charge,
            'beneficiary' => "Gtserviz Communication",
            'user_id' =>$user->id,
        ]);

        event(new AccountStatementEvent($user,$statement_name));

        return ['statement_link' => "URL", 'message' => "Account statement generated and sent to your mail"];

    }


    private function transaction_statement_to_html()
    {
        $result = ' <div style="">
 <img src="data:image/png;base64,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" style="width:100px;">
 <h2 style="margin-top: -40px">Gtserviz Communications</h2>
 <hr>
 <div>
 <h3>Hello,'. User::find($this->user_id)->full_name .'</h3>
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
       <p style="margin-top: -10px">Date: '.Carbon::now()->format('g:i a l jS F Y').'</p>
</div>
</div>';
        return $result;
    }

    private function convert_wallet_transaction_to_html()
    {
        if (WalletTransaction::where('user_id',$this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = WalletTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id',$this->user_id)->get();
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
        if (AirtimeTransaction::where('user_id',$this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = AirtimeTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id',$this->user_id)->get();;
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

        if (CableTransaction::where('user_id',$this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = CableTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id',$this->user_id)->get();;
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
        if (DataTransaction::where('user_id',$this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = DataTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id',$this->user_id)->get();
            $result = '<div> <h4 style="text-align: left;margin-bottom: -10px;margin-left: 10px">Data subscription transactions</h4><table style="width: 100%;padding: 10px;font-size: 10px;">

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
            <td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . $transaction->reference . '</td>
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
        if (ElectricityTransaction::where('user_id',$this->user_id)->get()->isNotEmpty()) {
            $wallet_transactions = ElectricityTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->where('user_id',$this->user_id)->get();
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
            <tr><td style="padding-top: 2px;padding-bottom: 2px;text-align: left">' . Carbon::parse($transaction->created_at)->format('g:i a l jS F Y') . '</td>
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