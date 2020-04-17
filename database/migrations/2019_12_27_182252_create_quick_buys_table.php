<?php

use App\Enums\ElectricityType;
use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
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
            $table->enum('network', [
                NetworkType::AIRTEL,
                NetworkType::GLO,
                NetworkType::MTN,
                NetworkType::NINE_MOBILE,
            ])->nullable();
            $table->string('meter_number')->nullable();
            $table->float('decoder')->nullable();;
            $table->string('phone')->nullable();;
            $table->string('decoder_number')->nullable();;
            $table->string('beneficiary_name')->nullable();;
            $table->string('data')->nullable();;
            $table->string('amount');
            $table->string('beneficiary')->nullable();;
            $table->string('plan')->nullable();;
            $table->enum('electricity_type',[
                ElectricityType::POSTPAID,
                ElectricityType::PREPAID
            ])->nullable();;
            $table->string('email')->nullable();;
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
        Schema::dropIfExists('quick_buys');
    }
}
