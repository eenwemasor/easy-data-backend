<?php

use App\Enums\ElectricityType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectricityTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electricity_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('meter_number');
            $table->string('beneficiary_name');
            $table->string('plan');
            $table->enum('type',[
                ElectricityType::PREPAID,
                ElectricityType::POSTPAID
            ]);
            $table->float('initial_balance')->nullable();
            $table->float('amount');
            $table->float('new_balance')->nullable();
            $table->enum('status', [
                TransactionStatus::SENT,
                TransactionStatus::COMPLETED,
                TransactionStatus::PROCESSING,
                TransactionStatus::FAILED,
            ]);
            $table->string('method');
            $table->string('token')->nullable();
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
        Schema::dropIfExists('electricity_transactions');
    }
}
