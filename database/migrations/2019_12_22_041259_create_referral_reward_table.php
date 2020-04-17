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
            $table->string('registration_fee');
            $table->string('direct_referrer_percentage');
            $table->string('indirect_referrer_percentage');
            $table->string('referee_percentage');
            $table->string('site_percentage');
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
