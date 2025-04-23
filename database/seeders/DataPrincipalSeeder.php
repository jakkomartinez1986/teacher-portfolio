<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Settings\School\Year;
use App\Models\Settings\School\Nivel;
use App\Models\Settings\School\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataPrincipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $currentYear = date('Y');
        
        // Calcular el año siguiente
        $nextYear = $currentYear + 1;
        
        // Formar el año lectivo en formato "gestión YYYY-YYYY"
        
        $anio = 'Gestion '.$currentYear . '-' . $nextYear;
        // Insertar el año lectivo en la base de datos
        $fechaInicio = Carbon::createFromFormat('Y-m-d', "{$currentYear}-08-01")->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', "{$nextYear}-07-31")->endOfDay();
        $aniocreated= Year::create([
            'year' => $anio,
            'star_date' => $fechaInicio,
            'end_date' =>  $fechaFin,
            'status' => 1,
        ]);
        $jornadas = [
            ['jornada' => 'MATUTINA'],
            ['jornada' => 'VESPERTINA'],
            ['jornada' => 'INTENSIVO'],
        ];
        foreach ($jornadas as $jornada) {
            Shift::create([
                'year_id' => $aniocreated->id,
                'shift_name' => $jornada['jornada'],
                'status' => 1, // O el valor que desees para el estado
            ]);
        }
        $jornanadamatutina = Shift::where('shift_name', 'MATUTINA')->first();
        $levels = [
            ['nivel_name' => 'Educación_Inicial'],
            ['nivel_name' => 'Educación_General_Básica_Preparatoria'],
            ['nivel_name' => 'Educación_General_Básica_Elemental'], 
            ['nivel_name' => 'Educación_General_Básica_Media'],
            ['nivel_name' => 'Educación_General_Básica_Superior'], 
            ['nivel_name' => 'Bachillerato_General_Unificado'], 
            ['nivel_name' => 'Bachillerato_Técnico_Inf'], 
            ['nivel_name' => 'Bachillerato_Técnico_Com'], 
            ['nivel_name' => 'Bachillerato_Técnico_Dep' ], 
            //['nivel' => 'Directivo', 'jornada' => 'Matutina'],
           
        ];

        // Insertar los niveles en la base de datos
        foreach ($levels as $level) {
            Nivel::create([
                'shift_id' => $jornanadamatutina->id,
                'nivel_name' => $level['nivel_name'],
                'status' => 1, // O el valor que desees para el estado
            ]);
        }

    }
}
