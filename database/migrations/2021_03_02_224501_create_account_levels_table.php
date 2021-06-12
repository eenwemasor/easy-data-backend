<?php

use App\Enums\CalculationMethodType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->float('cost_to_upgrade');
            $table->float('direct_referrer_percentage_bonus');
            $table->float('indirect_referrer_percentage_bonus');
            $table->float('wallet_deposit_direct_referrer_percentage_bonus');
            $table->float('wallet_deposit_indirect_referrer_percentage_bonus');
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
        Schema::dropIfExists('account_levels');
    }
}
