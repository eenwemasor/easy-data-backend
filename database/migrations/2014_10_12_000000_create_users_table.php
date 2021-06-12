<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone');
            $table->float('wallet');
            $table->string('accessibility');
            $table->string('email_confirmed');
            $table->string('phone_verified');
            $table->string('unique_id');
            $table->string('transaction_pin')->nullable();
            $table->boolean('active');
            $table->float('bonus_wallet');
            $table->string('password');
            $table->string('account_level_id');
            $table->string('username')->unique();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->string('monnify_account_number')->nullable();
            $table->string('monnify_bank_name')->nullable();
            $table->string('monnify_bank_code')->nullable();
            $table->string('monnify_collection_channel')->nullable();
            $table->string('monnify_reservation_channel')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
