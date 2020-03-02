<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCableTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('decoder');
            $table->string('decoder_number');
            $table->string('beneficiary_name');
            $table->string('plan');
            $table->float('initial_balance')->nullable();
            $table->float('price');
            $table->float('new_balance')->nullable();
            $table->string('status');
            $table->string('method');
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
        Schema::dropIfExists('cable_transactions');
    }
}
