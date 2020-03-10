<?php

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
            $table->string('reference_number');
            $table->float('amount');
            $table->float('initial_balance');
            $table->float('balance');
            $table->bigInteger('bank_id');
            $table->bigInteger('user_id');
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
