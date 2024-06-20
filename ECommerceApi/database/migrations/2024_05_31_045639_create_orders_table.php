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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('d_address');
            $table->double('total');
            $table->double('total_tax');
            $table->double('pay_amount');
            // $table->unsignedBigInteger('offer_id')->nullable();
            $table->double('discount_amount');
            $table->double('final_amount');
            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('offer_id')->references('id')->on('offers');
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
        Schema::dropIfExists('orders');
    }
};
