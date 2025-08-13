<?php

namespace App\Imports\Settings\Calendar;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Settings\Trimester\Trimester;
use App\Models\Settings\Calendar\CalendarDay;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class CalendarDaysImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $yearId;
    protected $customErrors = [];

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function model(array $row)
    {
        // Saltar filas vacías
        if (empty($row['fecha'])) {
            return null;
        }

        $date = $this->transformDate($row['fecha']);
        $isWeekend = $date->isWeekend();

        // Buscar trimestre
        $period = $this->normalizePeriod($row['periodo']);
        $trimester = Trimester::where('trimester_name', $period)->first();

        if (!$trimester) {
            return null;
        }

        return new CalendarDay([
            'year_id' => $this->yearId,
            'trimester_id' => $trimester->id,
            'period' => $period,
            'date' => $date,
            'month_name' => $row['mes_nombre'] ?? $this->getMonthNameFromDate($date),
            'day_name' => $this->normalizeDayName($row['dia_nombre']),
            'week' => $isWeekend ? null : (int)$row['semana'],
            'day_number' => $isWeekend ? null : (int)$row['num_dia'],
            'activity' => $row['actividad'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'periodo' => 'required|in:Primer Trimestre,Segundo Trimestre,Tercer Trimestre',
            'fecha' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Aceptar números (fechas seriales de Excel)
                    if (is_numeric($value)) return true;
                    
                    // Intentar parsear como fecha
                    try {
                        $this->transformDate($value);
                        return true;
                    } catch (\Exception $e) {
                        $fail('El campo fecha no es una fecha válida.');
                    }
                }
            ],
            'dia_nombre' => 'required|string|max:20',
            'semana' => 'required|integer',
            'mes_nombre' => 'nullable|string|max:20',
            'num_dia' => 'nullable|integer',
            'actividad' => 'nullable|string',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($validator->getData() as $key => $row) {
                $date = $this->transformDate($row['fecha']);
                $isWeekend = $date->isWeekend();

                if (!$isWeekend) {
                    if (empty($row['semana'])) {
                        $validator->errors()->add($key.'.semana', 'La semana es obligatoria para días laborales');
                    }
                    if (empty($row['num_dia'])) {
                        $validator->errors()->add($key.'.num_dia', 'El número de día es obligatorio para días laborales');
                    }
                }
            }
        });
    }
   
    public function getCustomErrors()
    {
        return $this->customErrors;
    }

    protected function normalizeRow(array $row): array
    {
        // Normalizar nombres de columnas alternativos
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[strtolower($key)] = $value;
        }
        return $normalized;
    }

    protected function normalizePeriod(?string $period): ?string
    {
        $period = trim($period ?? '');
        
        $map = [
            'primer trimestre' => 'Primer Trimestre',
            'segundo trimestre' => 'Segundo Trimestre',
            'tercer trimestre' => 'Tercer Trimestre',
            'primero' => 'Primer Trimestre',
            'segundo' => 'Segundo Trimestre',
            'tercero' => 'Tercer Trimestre',
        ];
        
        $lowerPeriod = strtolower($period);
        return $map[$lowerPeriod] ?? null;
    }

    protected function normalizeDayName(string $dayName): string
    {
        $dayName = mb_convert_case(trim($dayName), MB_CASE_TITLE);
        
        // Normalizar nombres de días
        $replacements = [
            'Miercoles' => 'Miércoles',
            'Miercole' => 'Miércoles',
            'Sabado' => 'Sábado',
            'Domingo' => 'Domingo',
            'Lunes' => 'Lunes',
            'Martes' => 'Martes',
            'Jueves' => 'Jueves',
            'Viernes' => 'Viernes',
        ];
        
        return $replacements[$dayName] ?? $dayName;
    }

  
    protected function getMonthNameFromDate(Carbon $date): string
    {
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        return $months[$date->month] ?? strtoupper($date->format('F'));
    }

        protected function transformDate($value)
    {
        // Si el valor es numérico, probablemente es una fecha serial de Excel
        if (is_numeric($value)) {
            try {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                throw new \Exception("El valor numérico {$value} no pudo ser convertido a fecha válida");
            }
        }
        
        // Si es string, intentar parsear en diferentes formatos
        try {
            // Intenta con formato día/mes/año (d/m/Y)
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value);
            }
            
            // Intenta con formato año-mes-día (Y-m-d)
            if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $value)) {
                return Carbon::createFromFormat('Y-m-d', $value);
            }
            
            // Intenta con cualquier formato que Carbon entienda
            return Carbon::parse($value);
            
        } catch (\Exception $e) {
            throw new \Exception("El valor '{$value}' no es una fecha válida");
        }
    }
}