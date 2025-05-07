<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\School\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Crear escuelas de prueba
        School::create([
            'name_school' => 'Unidad Educativa Vicente Leon',
            'distrit' => 'DISTRITO 05D01 - CIRCUITO C6_11 - AMIE 05H00091',
            'location' => 'Latacunga -Cotopaxi- Ecuador',
            'address' => 'Av.Tahuantinsuyo y Cañaris/Sector la Cocha ',           
            'phone' => '9999999999',
            'email' => 'info@uevicenteleon.com',
            'website' => 'https://uevicenteleon.com',
            'logo_path' => 'app-resources/img/logos/ue-vicente-leon.jpg',
            'status' => 1,
        ]);
    }
}
