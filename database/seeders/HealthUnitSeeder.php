<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;
use App\Models\HealthUnit;
use App\Models\Municipality;

class HealthUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sph = new HealthUnit();
        $sph->name = "Sarangani Provincial Hospital";
        $sph->type = "SPH";
        $sph->save();

        $consignor = new HealthUnit();
        $consignor->name = "Consignor";
        $consignor->type = "CNOR";
        $consignor->save();

        $municipalities = Municipality::get();

        foreach ($municipalities as $municipality) {
            $rhu = new HealthUnit();
            $rhu->name = $municipality->name . ' RHU';
            $rhu->municipality_id = $municipality->id;
            $rhu->type = "RHU";
            $rhu->save();
        }

        $brgys = Barangay::get();

        foreach ($brgys as $brgy) {
            $bhs = new HealthUnit();
            $bhs->name = $brgy->name . ' BHS';
            $bhs->municipality_id = $brgy->municipality_id;
            $bhs->barangay_id = $brgy->id;
            $bhs->type = "BHS";
            $bhs->save();
        }
    }
}
