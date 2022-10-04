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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['male', 'female','other'])->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('device_id')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_notify')->default(1);
            $table->boolean('status')->default(1);
            $table->string('password')->nullable();
            $table->enum('role', ['rider', 'driver', 'admin']);
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->enum('vehicle_type', ['car', 'suv', 'mini_Van']);
            $table->string('confirmation_code')->nullable();
            $table->boolean('is_online')->default(1);
            $table->bigInteger('login_count')->default(0);
            $table->boolean('is_broad')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->rememberToken();
            $table->timestamps('deleted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
