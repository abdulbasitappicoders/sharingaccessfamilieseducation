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
        Schema::table('ride_payments', function (Blueprint $table) {
            $table->float('commission_percentage')->nullable()->after('commission');
            $table->string('stripe_transfer_id')->nullable()->after('commission');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ride_payments', function (Blueprint $table) {
            //
        });
    }
};
