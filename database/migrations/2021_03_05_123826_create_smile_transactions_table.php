<?php

use App\Enums\SmileTransactionType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmileTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smile_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->float('amount');
            $table->string('smart_card_number');
            $table->string('beneficiary_name');
            $table->enum('transaction_type', SmileTransactionType::toArray());
            $table->float('initial_balance');
            $table->float('new_balance');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->enum('status',  TransactionStatus::toArray());
            $table->string('method');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('smile_price_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smile_transactions');
    }
}
