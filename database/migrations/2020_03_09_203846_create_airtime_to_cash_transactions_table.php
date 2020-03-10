<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirtimeToCashTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtime_to_cash_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_number');
            $table->string('network');
            $table->float('amount');
            $table->float('sender_phone');
            $table->float('recipient_phone');
            $table->float('initial_balance');
            $table->float('balance');
            $table->bigInteger('user_id');
            $table->string('status');
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
        Schema::dropIfExists('airtime_to_cash_transactions');
    }
}
