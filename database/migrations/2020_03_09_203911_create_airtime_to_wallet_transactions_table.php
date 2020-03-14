<?php

use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirtimeToWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtime_to_wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->enum('network', [
                NetworkType::AIRTEL,
                NetworkType::GLO,
                NetworkType::MTN,
                NetworkType::NINE_MOBILE,
            ]);
            $table->float('amount');
            $table->string('sender_phone');
            $table->string('recipient_phone');
            $table->float('initial_balance');
            $table->float('new_balance');
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('airtime_to_wallet_transactions');
    }
}
