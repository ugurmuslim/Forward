<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskPrerequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_prerequisites', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('task_detail_id')->unsigned();
            $table->foreign('task_detail_id')->references('id')->on('task_details');
            $table->json('prerequisites');
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
        Schema::dropIfExists('task_prerequisites');
    }
}
