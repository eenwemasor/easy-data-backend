<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultCheckerPinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_checker_pins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_number');
            $table->string('pin');
            $table->unsignedBigInteger('result_check_transaction_id');

            $table->foreign('result_check_transaction_id')->references('id')->on('result_check_transactions')->onDelete('cascade');
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
        Schema::dropIfExists('result_checker_pins');
    }
}
