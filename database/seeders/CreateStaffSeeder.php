<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('role', 'staff')->exists()) {
            User::create([
                'username' => 'staff',
                'first_name' => 'staff',
                'email' => 'staff@safe.com',
                'gender' => 'male',
                'vehicle_type' => 'car',
                'status' => '1',
                'role' => 'staff',
                'password' => Hash::make("123456789"),
            ]);
        }
    }
}
