<?php

use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawFundTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_fund_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->float('amount');
            $table->float('initial_balance');
            $table->float('new_balance');
            $table->bigInteger('bank_id');
            $table->bigInteger('user_id');
            $table->enum('status', [
                TransactionStatus::SENT,
                TransactionStatus::COMPLETED,
                TransactionStatus::PROCESSING,
                TransactionStatus::FAILED,
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_fund_transactions');
    }
}
