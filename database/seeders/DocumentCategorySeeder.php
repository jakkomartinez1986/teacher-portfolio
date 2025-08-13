<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Document\DocumentCategory;
use App\Models\Settings\Document\DocumentType;
use phpDocumentor\Reflection\PseudoTypes\True_;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear categorías de documentos
        $categories = [
            [
                'name' => 'Documentos Pedagógicos',
                'description' => 'Documentos relacionados con la planificación y evaluación pedagógica',
                'types' => [
                    [
                        'name' => 'Planificación Microcurricular',
                        'description' => 'Documentos de planificación microcurricular',
                        'frequency' => 'TRIMESTRAL',
                        'requires_director' => false,
                        'requires_vice_principal' => true,
                        'requires_principal' => true,
                        'requires_dece' => false
                    ],
                    [
                        'name' => 'Refuerzos Pedagógicos',
                        'description' => 'Documentos de refuerzos pedagógicos',
                        'frequency' => 'TRIMESTRAL',
                        'requires_director' => false,
                        'requires_vice_principal' => true,
                        'requires_principal' => true,
                        'requires_dece' => false
                    ],
                    [
                        'name' => 'Actas',
                        'description' => 'Actas de reuniones pedagógicas',
                        'frequency' => 'TRIMESTRAL',
                        'requires_director' => false,
                        'requires_vice_principal' => true,
                        'requires_principal' => true,
                        'requires_dece' => false
                    ],
                    [
                        'name' => 'Instrumentos de Evaluación',
                        'description' => 'Instrumentos utilizados para evaluación estudiantil',
                        'frequency' => 'TRIMESTRAL',
                        'requires_director' => false,
                        'requires_vice_principal' => true,
                        'requires_principal' => true,
                        'requires_dece' => false
                    ]
                ]
            ],
            [
                'name' => 'Tutoría',
                'description' => 'Documentos relacionados con actividades de tutoría estudiantil',
                'types' => [
                    [
                        'name' => 'Actas',
                        'description' => 'Actas de reuniones de tutoría',
                        'frequency' => 'TRIMESTRAL',
                        'requires_director' => false,
                        'requires_vice_principal' => true,
                        'requires_principal' => false,
                        'requires_dece' => false
                    ],
                    [
                        'name' => 'Evaluacion Socioemocional',
                        'description' => 'Evaluaciones del desarrollo socioemocional de los estudiantes',
                        'frequency' => 'ANUAL',
                        'requires_director' => false,
                        'requires_vice_principal' => true,
                        'requires_principal' => false,
                        'requires_dece' => true
                    ]
                ]
            ],
            [
                'name' => 'Documentos',
                'description' => 'Documentos generales de la institución',
                'types' => []
            ],
            [
                'name' => 'Otros',
                'description' => 'Documentos que no pertenecen a las otras categorías',
                'types' => []
            ]
        ];

        foreach ($categories as $categoryData) {
            // Crear la categoría
            $category = DocumentCategory::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description']
            ]);

            // Crear los tipos de documentos asociados a esta categoría
            foreach ($categoryData['types'] as $typeData) {
                DocumentType::create([
                    'category_id' => $category->id,
                    'name' => $typeData['name'],
                    'description' => $typeData['description'],
                    'frequency' => $typeData['frequency'],
                    'requires_director' => $typeData['requires_director'],
                    'requires_vice_principal' => $typeData['requires_vice_principal'],
                    'requires_principal' => $typeData['requires_principal'],
                    'requires_dece' => $typeData['requires_dece']
                ]);
            }
        }
    }
}