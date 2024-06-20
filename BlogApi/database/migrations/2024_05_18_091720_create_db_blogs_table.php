<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->unsignedBigInteger('db_user_id');
            $table->string('title');
            $table->string('des');
            $table->string('tag')->nullable();
            $table->string('blogimage');
            $table->foreign('db_user_id')->references('id')->on('db_users');
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
        Schema::dropIfExists('db_blogs');
    }
};
