<?php

use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->enum('network', NetworkType::toArray());
            $table->string('data');
            $table->float('initial_balance')->nullable();
            $table->float('amount');
            $table->string('beneficiary');
            $table->float('new_balance')->nullable();
            $table->enum('status', TransactionStatus::toArray());
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
        Schema::dropIfExists('data_transactions');
    }
}
