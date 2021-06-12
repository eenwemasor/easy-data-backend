<?php

use App\Enums\ApplicationMethodType;
use App\Enums\CalculationMethodType;
use App\Enums\ServiceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLevelApplicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_level_applicables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_level_id');
            $table->enum('service_type', ServiceType::toArray());
            $table->enum('application_method', ApplicationMethodType::toArray());
            $table->enum('calculation_method', CalculationMethodType::toArray());
            $table->float('value');
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
        Schema::dropIfExists('account_level_applicables');
    }
}
