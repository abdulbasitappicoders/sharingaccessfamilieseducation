<?php

namespace Database\Seeders;

use App\Models\AppVersionSetting;
use App\Models\RideType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppVersionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppVersionSetting::truncate();
        AppVersionSetting::insert([
            [
                'built_number' => '2',
                'app_version' => '2.1',
                'platform' => 'android'
            ],
            [
                'built_number' => '2',
                'app_version' => '2.1',
                'platform' => 'ios'
            ]
        ]);
    }
}
