<?php

namespace Database\Seeders;

use App\Models\LaboratoryTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratoryTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data1 = new LaboratoryTest();
        $data1->name = "X-ray";
        $data1->description = "X-ray - Medical Imaging";
        $data1->type = "imaging";
        $data1->save();

        $data2 = new LaboratoryTest();
        $data2->name = "(RT)-PCR";
        $data2->description = "(RT)-PCR - Reverse Transcription Polymerase Chain Reaction";
        $data2->type = "laboratory-test";
        $data2->save();

        $data3 = new LaboratoryTest();
        $data3->name = "Sputum Culture";
        $data3->description = "Sputum culture - Diagnostic test";
        $data3->type = "laboratory-test";
        $data3->save();
    }
}
