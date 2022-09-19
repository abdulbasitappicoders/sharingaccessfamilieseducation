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
        Schema::create('ride_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->nullable();
            $table->enum('type',['card','bank'])->nullable();
            $table->string('base_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->boolean('is_paid')->default(1);
            $table->foreignId('user_card_id')->nullable();
            $table->foreignId('driver_id')->nullable();
            $table->foreignId('rider_id')->nullable();
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
        Schema::dropIfExists('ride_payments');
    }
};
