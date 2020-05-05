<?php

use App\Enums\NetworkType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataPlanListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_plan_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('network', [
                NetworkType::AIRTEL,
                NetworkType::GLO,
                NetworkType::MTN,
                NetworkType::NINE_MOBILE,
            ]);
            $table->float('vendor_amount')->nullable();
            $table->string('product_code')->nullable();
            $table->string('plan');
            $table->string('amount');
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
        Schema::dropIfExists('data_plan_lists');
    }
}
