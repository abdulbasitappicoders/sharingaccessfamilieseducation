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
        Schema::create('ride_locations', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('longitude');
            $table->string('latitude');
            $table->integer('ride_order')->default(1);
            $table->enum('status',['pending','completed'])->default('pending');
            $table->foreignId('ride_id')->nullable();
            $table->foreignId('user_children_id')->nullable();
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
        Schema::dropIfExists('ride_locations');
    }
};
