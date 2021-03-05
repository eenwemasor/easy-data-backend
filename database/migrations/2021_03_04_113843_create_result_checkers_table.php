<?php

use App\Enums\ResultCheckerExamBody;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultCheckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_checkers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('examination_body',[
                ResultCheckerExamBody::NECO,
                ResultCheckerExamBody::WAEC,
            ]);
            $table->float('price');
            $table->float('vendor_price');
            $table->string('product_code');
            $table->string('description');
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
        Schema::dropIfExists('result_checkers');
    }
}
