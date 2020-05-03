<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminChannelUtilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_channel_utils', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone');
            $table->string('email');
            $table->string('glo_discount');
            $table->string('airtel_discount');
            $table->string('mtn_discount');
            $table->string('etisalat_discount');
            $table->float('statement_request_charge');
            $table->float('paystack_transaction_fee');
            $table->float('paystack_fund_wallet_fee');
            $table->float('sms_unit_charge');
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
        Schema::dropIfExists('admin_channel_utils');
    }
}
