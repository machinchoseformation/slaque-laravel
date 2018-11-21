<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->boolean('edited')->default(false);
            $table->boolean('deleted')->default(false);
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->integer('to_id')->unsigned();
            $table->foreign('to_id')->references('id')->on('users');
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
        Schema::dropIfExists('direct_messages');
    }
}
