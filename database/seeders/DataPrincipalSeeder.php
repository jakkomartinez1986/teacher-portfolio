<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Settings\Area\Area;
use App\Models\Settings\School\Year;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\Settings\School\Nivel;
use App\Models\Settings\School\Shift;
use App\Models\Settings\School\School;
use App\Models\Settings\Trimester\Trimester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataPrincipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = 2025;
        $nextYear = $currentYear + 1;
        $anio = $currentYear . '-' . $nextYear;
        
        $fechaInicio = Carbon::createFromFormat('Y-m-d', "{$currentYear}-08-12")->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', "{$nextYear}-07-10")->endOfDay();
        
        // Crear escuela
        $school = School::create([
            'name_school' => 'Unidad Educativa Vicente Leon',
            'distrit' => 'DISTRITO 05D01 - CIRCUITO C6_11 - AMIE 05H00091',
            'location' => 'Latacunga -Cotopaxi- Ecuador',
            'address' => 'Av.Tahuantinsuyo y Cañaris/Sector la Cocha',           
            'phone' => '9999999999',
            'email' => 'info@uevicenteleon.com',
            'website' => 'https://uevicenteleon.com',
            'logo_path' => 'app-resources/img/logos/ue-vicente-leon.jpg',
            'status' => 1,
        ]);
        
        // Crear año escolar
        $year = Year::firstOrCreate([
            'school_id' => $school->id,
            'year_name' => $anio,
            'start_date' => $fechaInicio,
            'end_date' => $fechaFin,
            'status' => 1
        ]);
        
        // [Resto del código para shifts, niveles y grados...]
        
        // Crear trimestres asociados al año escolar
        $trimestres = [
            [
                'year_id' => $year->id,
                'trimester_name' => 'Primer Trimestre',
                'start_date' => Carbon::create($currentYear, 9, 1)->startOfDay(),
                'end_date' => Carbon::create($currentYear, 12, 5)->endOfDay(),
                'status' => 1
            ],
            [
                'year_id' => $year->id,
                'trimester_name' => 'Segundo Trimestre',
                'start_date' => Carbon::create($currentYear, 12, 8)->startOfDay(),
                'end_date' => Carbon::create($nextYear, 3, 20)->endOfDay(),
                'status' => 1
            ],
            [
                'year_id' => $year->id,
                'trimester_name' => 'Tercer Trimestre',
                'start_date' => Carbon::create($nextYear, 3, 23)->startOfDay(),
                'end_date' => $fechaFin, // Usamos la misma fecha de fin del año escolar
                'status' => 1
            ]
        ];

        foreach ($trimestres as $trimestreData) {
            // Verificar que las fechas estén dentro del rango del año escolar
            if ($trimestreData['start_date']->lt($year->start_date)) {
                $this->command->warn("Ajustando fecha de inicio del trimestre {$trimestreData['trimester_name']} para que no sea anterior al inicio del año escolar");
                $trimestreData['start_date'] = $year->start_date->copy();
            }

            if ($trimestreData['end_date']->gt($year->end_date)) {
                $this->command->warn("Ajustando fecha de fin del trimestre {$trimestreData['trimester_name']} para que no sea posterior al fin del año escolar");
                $trimestreData['end_date'] = $year->end_date->copy();
            }

            // Verificar que no se solapen los trimestres
            $existingTrimester = Trimester::where('year_id', $year->id)
                ->where(function($query) use ($trimestreData) {
                    $query->whereBetween('start_date', [$trimestreData['start_date'], $trimestreData['end_date']])
                          ->orWhereBetween('end_date', [$trimestreData['start_date'], $trimestreData['end_date']])
                          ->orWhere(function($q) use ($trimestreData) {
                              $q->where('start_date', '<=', $trimestreData['start_date'])
                                ->where('end_date', '>=', $trimestreData['end_date']);
                          });
                })
                ->exists();

            if ($existingTrimester) {
                $this->command->error("No se pudo crear el trimestre {$trimestreData['trimester_name']} porque se solapa con otro trimestre existente");
                continue;
            }

            // Crear el trimestre
            Trimester::firstOrCreate(
                [
                    'year_id' => $year->id,
                    'trimester_name' => $trimestreData['trimester_name']
                ],
                $trimestreData
            );

            $this->command->info("Trimestre {$trimestreData['trimester_name']} creado: {$trimestreData['start_date']->format('Y-m-d')} al {$trimestreData['end_date']->format('Y-m-d')}");
        }

         // Crear turnos
               $shifts = [
                ['shift_name' => 'MATUTINA', 'status' => 1],
                ['shift_name' => 'VESPERTINA', 'status' => 1],
                ['shift_name' => 'INTENSIVO', 'status' => 0],
            ];
    
            foreach ($shifts as $shiftData) {
                $shift = Shift::firstOrCreate([
                    'year_id' => $year->id,
                    'shift_name' => $shiftData['shift_name'],
                    'status' => $shiftData['status']
                ]);
    
                // Crear niveles para cada turno
                $niveles = [
                    ['nivel_name' => 'Educación_Inicial', 'status' => 1],
                    ['nivel_name' => 'Educación_General_Básica_Preparatoria', 'status' => 1],
                    ['nivel_name' => 'Educación_General_Básica_Media', 'status' => 1],
                    ['nivel_name' => 'Educación_General_Básica_Superior', 'status' => 1],
                    ['nivel_name' => 'Bachillerato_General_Unificado', 'status' => 1],
                    ['nivel_name' => 'Bachillerato_Técnico_Inf', 'status' => 1],
                    ['nivel_name' => 'Bachillerato_Técnico_Com', 'status' => 1],
                    ['nivel_name' => 'Bachillerato_Técnico_Dep', 'status' => 1],                  
                ];
                //strtoupper(
                foreach ($niveles as $nivelData) {
                    $nivel = Nivel::firstOrCreate([
                        'shift_id' => $shift->id,
                        'nivel_name' => $nivelData['nivel_name'],
                        'status' => $nivelData['status']
                    ]);
    
                    // Crear grados para cada nivel
                    $grados = [];
                    if ($nivelData['nivel_name'] == 'Educación_Inicial') {
                        $grados = [];
                        // Crear grados 1 y 2
                        foreach ([1, 2] as $gradoNum) {
                            // Crear secciones de A a F para cada grado
                            foreach (range('A', 'F') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° Educación Inicial',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }                    
                    } elseif ($nivelData['nivel_name'] == 'Educación_General_Básica_Preparatoria') {
                        foreach (range(1, 2) as $gradoNum) {
                            foreach (range('A', 'F') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° EGB Preparatoria',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }  elseif ($nivelData['nivel_name'] == 'Educación_General_Básica_Elemental') {
                        foreach (range(3, 4) as $gradoNum) {
                            foreach (range('A', 'F') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° EGB Basica Elemental',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    } elseif ($nivelData['nivel_name'] == 'Educación_General_Básica_Media') {
                        foreach (range(5, 7) as $gradoNum) {
                            foreach (range('A', 'F') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° EGB Basica Media',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }elseif ($nivelData['nivel_name'] == 'Educación_General_Básica_Superior') {
                        foreach (range(5, 7) as $gradoNum) {
                            foreach (range('A', 'F') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° EGB Basica Superior',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }
                    elseif ($nivelData['nivel_name'] == 'Bachillerato_General_Unificado') {
                        foreach (range(1, 3) as $gradoNum) {
                            foreach (range('A', 'C') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° BGU General Unificado',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }
                    elseif ($nivelData['nivel_name'] == 'Bachillerato_Técnico_Inf') {
                        foreach (range(1, 3) as $gradoNum) {
                            foreach (range('A', 'C') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° BT Técnico Inf',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }
                    elseif ($nivelData['nivel_name'] == 'Bachillerato_Técnico_Com') {
                        foreach (range(1, 3) as $gradoNum) {
                            foreach (range('A', 'C') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° BT Técnico Com',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }
                    elseif ($nivelData['nivel_name'] == 'Bachillerato_Técnico_Dep') {
                        foreach (range(1, 3) as $gradoNum) {
                            foreach (range('A', 'C') as $seccion) {
                                $grados[] = [
                                    'grade_name' => $gradoNum.'° BT Técnico Dep',
                                    'section' => $seccion,
                                    'status' => 1
                                ];
                            }
                        }
                    }                   
    
                    foreach ($grados as $gradoData) {
                        Grade::firstOrCreate([
                            'nivel_id' => $nivel->id,
                            'grade_name' => $gradoData['grade_name'],
                            'section' => $gradoData['section'],
                            'status' => $gradoData['status']
                        ]);
                    }
                }
            }

    // Crear áreas
        $areas = [
            'Inicial',
            'Basica Preparatoria',
            'Basica Media',
            'Ciencias Naturales, Biologia y Fisica',
            'Educación Cultural y Artística',
            'Estudios Sociales',
            'Matematica',
            'Lengua Extranjera',
            'Lengua y Literatura',
            'BT Comercio y Ventas -Emprendimiento',
            'BT Deportes y Recreacion-Educación Física',
            'BT Informatica',
            'Optativas',
            'Tutoria',
        ];

        foreach ($areas as $areaName) {
            $area = Area::firstOrCreate(['area_name' => $areaName]);

            // Crear materias para cada área
            $materias = [];
            switch ($areaName) {
                case 'Inicial':
                    $materias = [ 'Currículo Integrado por ámbitos de aprendizaje'];
                    break;
                case 'Basica Preparatoria':
                    $materias = [ 'Currículo Integrado por ámbitos'];
                    break;
                case 'Basica Media':
                        $materias = [ 'Matemáticas', 'Ciencias Naturales', 'Lengua y Literatura', 'Estudios Sociales'];
                        break;
                case 'Ciencias Naturales, Biologia y Fisica':
                    $materias = [ 'Ciencias Naturales',
                    'Química',
                    'Quimica Superior',
                    'Biología',
                    'Biología Superior',
                    'Fisica',
                    'Fisica Superior'];
                    break;
                case 'Educación Cultural y Artística':
                    $materias = [ 'Educación Cultural y Artística',
                    'Dibujo Técnico Aplicado a Comercialización y Ventas'];
                    break;
                case 'Estudios Sociales':
                    $materias = ['Estudios Sociales',
                    'Filosofía',
                    'Historia',
                    'Educación para la Ciudadanía',
                    'Investigacion Ciencia y Tecnoclogia'];
                    break;
                case 'Matematica':
                    $materias = [ 'Matemáticas',
                    'Matematica Superior'];
                    break;
                case 'Lengua Extranjera':
                    $materias = [ 'Inglés',
                    'Inglés Técnico Aplicado a Comercialización y Ventas'];
                    break;
                case 'Lengua y Literatura':
                    $materias = ['Lengua y Literatura',
                    'Animación a la lectura'];
                    break;
                case 'BT Comercio y Ventas -Emprendimiento':
                    $materias = ['Emprendimiento y Gestión',
                    'Animación en el Punto de Venta',
                    'Operaciones de Venta',
                    'Operaciones de Almacenaje',
                    'Informática Aplicada a Comercialización y Ventas',
                    'Formación y Orientación Laboral - FOL-COMER'];
                    break;
                case 'BT Deportes y Recreacion-Educación Física':
                    $materias = ['Bases Fisiológicas',
                    'Actividades Recreativas',
                    'Entrenamiento Deportivo',
                    'Educación Física',
                    'Manejo de Grupos',
                    'Organización de Eventos Recreativos y/o Deportivos',
                    'Planificación y Evaluación en Recreación y Deportes',
                    'Recursos Recreativos y Deportivos',
                    'Seguridad y Primeros Auxilios'
                    ];
                    break;
                case 'BT Informatica':
                    $materias = [ 'Programación y Bases de Datos',
                    'Diseño y Desarrollo WEB',
                    'Soporte Técnico',
                    'Sistemas Operativos y Redes',
                    'Aplicaciones Ofimáticas Locales y en Línea',
                    'Formación y Orientación Laboral - FOL-INFOR'];
                    break;
                case 'Optativas':
                    $materias = [  'Asignaturas optativas',
                    'Orientación vocacional y profesional'];
                    break;
                case 'Tutoria':
                    $materias = [ 'Acompañamiento integral en el aula',
                    'Cívica'];
                    break;
            }

            foreach ($materias as $materia) {
                Subject::firstOrCreate([
                    'area_id' => $area->id,
                    'subject_name' => $materia
                ]);
            }
        }


        $this->command->info('Datos iniciales de la escuela creados exitosamente!');
    }
}