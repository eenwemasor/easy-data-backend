<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuickBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quick_buys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('transaction_type');
            $table->string('network')->nullable();
            $table->string('plan')->nullable();
            $table->float('amount');
            $table->string('beneficiary');
            $table->string('email');
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
        Schema::dropIfExists('quick_buys');
    }
}
