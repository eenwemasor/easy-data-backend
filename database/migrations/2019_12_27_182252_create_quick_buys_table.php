<?php

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
            $table->string('plan')->nullable();
            $table->float('amount');
            $table->string('beneficiary');
            $table->string('email');
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
