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
        Schema::create('feed_backs', function (Blueprint $table) {
            $table->id();$table->string('name');
            $table->string('city');
            $table->BigInteger('phone');
            $table->string('email');
            $table->string('sFriendly');
            $table->string('sAnswer');
            $table->string('sResolve');
            $table->string('rate');
            $table->string('recommend');
            $table->string('quality');
            $table->string('question');
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
        Schema::dropIfExists('feed_backs');
    }
};
