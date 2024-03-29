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
        Schema::create('driver_insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('exp_date')->nullable();
            $table->string('number')->nullable();
            $table->string('front')->nullable();
            $table->string('back')->nullable();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('driver_insurances');
    }
};
