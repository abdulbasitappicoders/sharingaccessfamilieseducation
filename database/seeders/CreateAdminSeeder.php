<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'first_name' => 'admin',
                'email' => 'admin@safe.com',
                'gender' => 'male',
                'vehicle_type' => 'car',
                'status' => '1',
                'role' => 'admin',
                'password' => Hash::make("123456789"),
            ]);
        }
    }
}
