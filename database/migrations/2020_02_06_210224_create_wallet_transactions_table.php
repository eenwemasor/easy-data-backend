<?php

use App\Enums\WalletTransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('transaction_type');
            $table->string('description');
            $table->float('amount');
            $table->float('initial_balance');
            $table->float('new_balance');
            $table->string('wallet');
            $table->string('beneficiary')->nullable();
            $table->enum('status', [
                WalletTransactionStatus::SUCCESSFUL,
                WalletTransactionStatus::FAILED,
            ]);
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
        Schema::dropIfExists('wallet_transactions');
    }
}
