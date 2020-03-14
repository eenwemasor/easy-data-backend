<?php

use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftcardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giftcard_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('gift_card_type');
            $table->float('amount_to_sell');
            $table->float('amount_to_receive');
            $table->float('initial_balance');
            $table->float('new_balance');
            $table->float('user_id');
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
        Schema::dropIfExists('giftcard_transactions');
    }

}
