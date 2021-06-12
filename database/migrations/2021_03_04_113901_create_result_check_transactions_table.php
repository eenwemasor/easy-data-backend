<?php

use App\Enums\TransactionStatus;
use App\Enums\WalletType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultCheckTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_check_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->float('amount');
            $table->float('initial_balance');
            $table->float('new_balance');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('result_checker_id');
            $table->enum('status', TransactionStatus::toArray());
            $table->enum('wallet',WalletType::toArray());

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('result_checker_id')->references('id')->on('result_checkers')->onDelete('cascade');;
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
        Schema::dropIfExists('result_check_transactions');
    }
}
