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
            $table->float('commission')->nullable()->after('total_amount');
            $table->float('rider_amount')->nullable()->after('total_amount');
            $table->float('driver_ammount')->nullable()->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
