<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('done')->default(false);
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->timestamps();
        });

        Schema::table('tasks', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropForeign(['user_id']);
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
        });
        Schema::dropIfExists('tasks');
    }
}
