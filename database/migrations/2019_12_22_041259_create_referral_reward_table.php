<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('registration_fee');
            $table->float('direct_referrer_percentage');
            $table->float('indirect_referrer_percentage');
            $table->float('referee_percentage');
            $table->float('site_percentage');
            $table->float('direct_referrer_percentage_wallet_funding');
            $table->float('indirect_referrer_percentage_wallet_funding');
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
        Schema::dropIfExists('referral_rewards');
    }
}
