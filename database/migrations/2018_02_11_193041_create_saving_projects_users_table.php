<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingProjectsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_user', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount')->unsigned()->default(0);
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                                      ->on('users')
                                      ->onDelete('cascade');

            $table->integer('saving_id')->unsigned();
            $table->foreign('saving_id')->references('id')
                                        ->on('savings')
                                        ->onDelete('cascade');

            $table->unique(['saving_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saving_user');
    }
}
