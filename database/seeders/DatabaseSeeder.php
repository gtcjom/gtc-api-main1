<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Disease;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Disease::query()->truncate();
        Disease::query()->insert([
           [
               'name' => 'Acute Respiratory Infection',
               'type' => 'communicable'
           ],
            [
                'name' => 'Influenza A (H1N1)',
                'type' => 'communicable'
            ],
            [
                'name' => 'Bird Flu (Avian Influenza)',
                'type' => 'communicable'
            ],
            [
                'name' => 'Chickenpox',
                'type' => 'communicable'
            ],
            [
                'name' => 'Cholera',
                'type' => 'communicable'
            ],
            [
                'name' => 'Dengue',
                'type' => 'communicable'
            ],[
                'name' => 'Diarrhea',
                'type' => 'communicable'
            ],
            [
                'name' => 'Diphtheria',
                'type' => 'communicable'
            ],
            [
                'name' => 'Ebola',
                'type' => 'communicable'
            ],
            [
                'name' => 'Hand, Foot, and Mouth Disease',
                'type' => 'communicable'
            ],
            [
                'name' => 'Hepatitis A',
                'type' => 'communicable'
            ],[
                'name' => 'Hepatitis B',
                'type' => 'communicable'
            ],
            [
                'name' => 'Hepatitis C',
                'type' => 'communicable'
            ],
            [
                'name' => 'HIV/AIDS',
                'type' => 'communicable'
            ],
            [
                'name' => 'Influenza',
                'type' => 'communicable'
            ],

            [
                'name' => 'Leprosy',
                'type' => 'communicable'
            ],
            [
                'name' => 'Malaria',
                'type' => 'communicable'
            ],
            [
                'name' => 'Measles',
                'type' => 'communicable'
            ],
            [
                'name' => 'Meningococcemia',
                'type' => 'communicable'
            ],
            [
                'name' => 'Pertussis',
                'type' => 'communicable'
            ],
            [
                'name' => 'Poliomyelitis',
                'type' => 'communicable'
            ],
            [
                'name' => 'Rabies',
                'type' => 'communicable'
            ],
            [
                'name' => 'Severe Acute Respiratory Syndrome (SARS)',
                'type' => 'communicable'
            ],
            [
                'name' => 'Sore Eyes',
                'type' => 'communicable'
            ],
            [
                'name' => 'Tuberculosis',
                'type' => 'communicable'
            ],
            [
                'name' => 'Typhoid Fever',
                'type' => 'communicable'
            ],
            [
                'name' => "Alzheimer's",
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Cancer',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Epilepsy',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Osteoarthritis',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Osteoporosis',
                'type' => 'non-communicable'
            ],[
                'name' => 'Cerebrovascular Disease (Stroke)',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Chronic Obstructive Pulmonary Disease (COPD)',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Coronary Artery Disease',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Heat Stroke',
                'type' => 'non-communicable'
            ],

            [
                'name' => 'High Blood Pressure or Hypertension',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Obesity and Overweight',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Diabetes',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Depressive Disorders',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Substance Abuse: Alcohol',
                'type' => 'non-communicable'
            ],
            [
                'name' => 'Substance Abuse: Ecstasy',
                'type' => 'non-communicable'
            ],
        ]);





    }
}
