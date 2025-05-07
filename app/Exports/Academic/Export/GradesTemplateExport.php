<?php

namespace App\Exports\Academic\Export;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class GradesTemplateExport implements FromCollection , WithHeadings, WithMapping
{
    protected $students;
    protected $activities;

    public function __construct($students, $activities)
    {
        $this->students = $students;
        $this->activities = $activities;
    }
    public function collection()
    {
        return $this->students;
    }
    public function headings(): array
    {
        $headings = ['ID', 'Apellidos', 'Nombres'];

        foreach ($this->activities as $activity) {
            $headings[] = $activity->name;
        }

        $headings[] = 'Examen';
        $headings[] = 'Proyecto';

        return $headings;
    }

    public function map($student): array
    {
        $row = [
            $student->id,
            $student->lastname,
            $student->name
        ];

        foreach ($this->activities as $activity) {
            $row[] = ''; // Celda vacía para la nota
        }

        $row[] = ''; // Celda vacía para examen
        $row[] = ''; // Celda vacía para proyecto

        return $row;
    }
}
