<?php

use App\Enums\BulkSMSStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkSMSSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_s_m_s_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('sender_id');
            $table->text('receivers');
            $table->text('message');
            $table->float('amount');
            $table->enum('status',BulkSMSStatus::toArray());
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('bulk_s_m_s_s');
    }
}
