<?php

use App\Enums\DataType;
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
            $table->enum('network', NetworkType::toArray());
            $table->enum('type', DataType::toArray());
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
