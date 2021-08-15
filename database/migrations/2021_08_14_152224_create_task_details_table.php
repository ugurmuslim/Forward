<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_details', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->string('title');
            $table->integer('task_type_id')->unsigned();
            $table->foreign('task_type_id')->references('id')->on('task_types');
            $table->json('additional_fields')->nullable();
            $table->enum('status',['CANCELED','APPROVED'])->nullable();
            $table->string('prerequisites')->nullable();
            $table->timestamp('last_status_updated_on')->nullable();
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
        Schema::dropIfExists('task_details');
    }
}
