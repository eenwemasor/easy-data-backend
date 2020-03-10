<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferFundTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_fund_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_number');
            $table->float('amount');
            $table->float('initial_balance');
            $table->float('balance');
            $table->bigInteger('recipient_id');
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
        Schema::dropIfExists('transfer_fund_transactions');
    }
}
