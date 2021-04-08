<?php

use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->float('initial_balance');
            $table->float('amount');
            $table->string('transfer_code')->nullable();
            $table->string('description');
            $table->string('transfer_reference')->nullable();
            $table->string('transfer_id')->nullable();
            $table->float('new_balance');
            $table->enum('status', TransactionStatus::toArray());
            $table->string('method');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('withdrawal_transactions');
    }
}
