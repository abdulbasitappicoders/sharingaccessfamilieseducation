<?php

namespace Database\Seeders;

use App\Models\AppEmergencyNumber;
use App\Models\ChargesPerMile;
use App\Models\Commission;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppEmergencyNumber::firstOrNew([
            'emergency_number' => "2145642167"
        ])->save();

        ChargesPerMile::firstOrNew([
            'charges_per_mile' => "20"
        ])->save();

        Page::firstOrNew([
            'termsCondition' => null,
            'privacyPolicy' => null,
            'help' => null,
            'communityGroup' => null,
        ])->save();

        Commission::firstOrNew([
            'commission' => "2.5"
        ])->save();
    }
}
