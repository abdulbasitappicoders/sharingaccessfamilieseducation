<?php

namespace Database\Seeders;

use App\Models\RideType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RideTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RideType::truncate();
        RideType::insert([
            [
                'name' => 'car',
                'price' => '0.99'
            ],
            [
                'name' => 'suv',
                'price' => '0.99'
            ],
            [
                'name' => 'mini van',
                'price' => '0.99'
            ]
        ]);
    }
}
