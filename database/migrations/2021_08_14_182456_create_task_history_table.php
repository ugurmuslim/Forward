<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_detail_history', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->bigInteger('task_detail_id')->unsigned();
            $table->foreign('task_detail_id')->references('id')->on('task_details');
            $table->enum('status',['CANCELED','APPROVED']);
            $table->timestamp('task_updated_on');
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
        Schema::dropIfExists('task_detail_history');
    }
}
