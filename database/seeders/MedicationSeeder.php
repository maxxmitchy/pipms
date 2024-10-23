<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicationSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $medications = [
            [
                'name' => 'Lisinopril',
                'description' => 'ACE inhibitor used to treat high blood pressure and heart failure',
                'dosage' => '10mg, 20mg, 40mg',
                'manufacturer' => 'Various',
                'brand_id' => 1, // Assuming 1 is the ID for Generic
            ],
            [
                'name' => 'Metformin',
                'description' => 'Oral diabetes medicine that helps control blood sugar levels',
                'dosage' => '500mg, 850mg, 1000mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Amlodipine',
                'description' => 'Calcium channel blocker used to treat high blood pressure and coronary artery disease',
                'dosage' => '2.5mg, 5mg, 10mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Metoprolol',
                'description' => 'Beta-blocker used to treat high blood pressure, chest pain, and heart failure',
                'dosage' => '25mg, 50mg, 100mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Omeprazole',
                'description' => 'Proton pump inhibitor used to treat acid reflux and ulcers',
                'dosage' => '10mg, 20mg, 40mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Levothyroxine',
                'description' => 'Thyroid hormone used to treat hypothyroidism',
                'dosage' => '25mcg, 50mcg, 75mcg, 100mcg, 125mcg, 150mcg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Atorvastatin',
                'description' => 'Statin medication used to lower cholesterol',
                'dosage' => '10mg, 20mg, 40mg, 80mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Sertraline',
                'description' => 'SSRI antidepressant used to treat depression, anxiety, and other mental health conditions',
                'dosage' => '25mg, 50mg, 100mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Gabapentin',
                'description' => 'Anticonvulsant and nerve pain medication',
                'dosage' => '100mg, 300mg, 400mg, 600mg, 800mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Hydrocodone/Acetaminophen',
                'description' => 'Combination pain medication',
                'dosage' => '5-325mg, 7.5-325mg, 10-325mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Escitalopram',
                'description' => 'SSRI antidepressant used to treat depression and anxiety',
                'dosage' => '5mg, 10mg, 20mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Albuterol',
                'description' => 'Bronchodilator used to treat asthma and COPD',
                'dosage' => '90mcg/actuation (inhaler)',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Losartan',
                'description' => 'Angiotensin II receptor blocker used to treat high blood pressure',
                'dosage' => '25mg, 50mg, 100mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Simvastatin',
                'description' => 'Statin medication used to lower cholesterol',
                'dosage' => '10mg, 20mg, 40mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
            [
                'name' => 'Metformin Extended-Release',
                'description' => 'Long-acting form of metformin for diabetes management',
                'dosage' => '500mg, 750mg, 1000mg',
                'manufacturer' => 'Various',
                'brand_id' => 1,
            ],
        ];

        foreach ($medications as $medication) {
            DB::table('medications')->insert([
                'name' => $medication['name'],
                'description' => $medication['description'],
                'dosage' => $medication['dosage'],
                'manufacturer' => $medication['manufacturer'],
                'brand_id' => $medication['brand_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add more varied medications with brand names
        $brandedMedications = [
            [
                'name' => 'Lipitor',
                'description' => 'Statin medication used to lower cholesterol',
                'dosage' => '10mg, 20mg, 40mg, 80mg',
                'manufacturer' => 'Pfizer',
                'brand_id' => 1, // Assuming 2 is the ID for Pfizer
            ],
            [
                'name' => 'Prozac',
                'description' => 'SSRI antidepressant used to treat depression, OCD, and other conditions',
                'dosage' => '10mg, 20mg, 40mg',
                'manufacturer' => 'Eli Lilly',
                'brand_id' => 1, // Assuming 3 is the ID for Eli Lilly
            ],
            [
                'name' => 'Nexium',
                'description' => 'Proton pump inhibitor used to treat acid reflux and ulcers',
                'dosage' => '20mg, 40mg',
                'manufacturer' => 'AstraZeneca',
                'brand_id' => 1, // Assuming 4 is the ID for AstraZeneca
            ],
            [
                'name' => 'Advair',
                'description' => 'Combination medication for asthma and COPD',
                'dosage' => '100/50mcg, 250/50mcg, 500/50mcg',
                'manufacturer' => 'GlaxoSmithKline',
                'brand_id' => 1, // Assuming 5 is the ID for GlaxoSmithKline
            ],
            [
                'name' => 'Lantus',
                'description' => 'Long-acting insulin for diabetes management',
                'dosage' => '100 units/mL',
                'manufacturer' => 'Sanofi',
                'brand_id' => 1, // Assuming 6 is the ID for Sanofi
            ],
        ];

        foreach ($brandedMedications as $medication) {
            DB::table('medications')->insert([
                'name' => $medication['name'],
                'description' => $medication['description'],
                'dosage' => $medication['dosage'],
                'manufacturer' => $medication['manufacturer'],
                'brand_id' => $medication['brand_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add more generic medications to increase the list
        $additionalGenerics = [
            'Amoxicillin', 'Ibuprofen', 'Acetaminophen', 'Aspirin', 'Naproxen',
            'Fluoxetine', 'Citalopram', 'Bupropion', 'Venlafaxine', 'Duloxetine',
            'Lorazepam', 'Alprazolam', 'Clonazepam', 'Diazepam', 'Zolpidem',
            'Metoprolol', 'Atenolol', 'Propranolol', 'Carvedilol', 'Bisoprolol',
            'Furosemide', 'Hydrochlorothiazide', 'Spironolactone', 'Torsemide', 'Bumetanide',
        ];

        foreach ($additionalGenerics as $genericName) {
            DB::table('medications')->insert([
                'name' => $genericName,
                'description' => $faker->sentence(),
                'dosage' => $faker->randomElement(['5mg', '10mg', '20mg', '25mg', '50mg', '100mg']),
                'manufacturer' => 'Various',
                'brand_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
