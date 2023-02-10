<?php

namespace Database\Seeders;

use App\Models\PaymentWithCardBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentWithCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentWithCardBank::firstOrNew(
            [
                'card_payment' => 'Abc',
                'bank_payment' => 'Abc',
            ]
        )->save();
    }
}
