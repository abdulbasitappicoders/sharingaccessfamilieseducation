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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->nullable();
            $table->foreignId('driver_id')->nullable();
            $table->dateTime('request_time')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('schedule_start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('wait_time')->nullable();
            $table->float('estimated_distance')->nullable();
            $table->float('estimated_time')->nullable();
            $table->float('estimated_price')->nullable();
            $table->enum('status',['accepted','rejected','requested','completed','canceled','confirmed'])->nullable();
            $table->enum('driver_status',['started','accepted','rejected','completed'])->nullable();
            $table->enum('ride_for',['self','children'])->nullable();
            $table->enum('type',['schedule','normal'])->nullable();
            $table->string('vehicle_type')->nullable();
            $table->integer('payment_method_id')->nullable();
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
        Schema::dropIfExists('rides');
    }
};
