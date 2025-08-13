<?php

namespace App\Imports\System\Student;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\System\Student\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $gradeId;
    protected $enrollmentDate;
    protected $schoolId;
    protected $successCount = 0;
    protected $errorCount = 0;
    protected $errors = [];

    public function __construct($gradeId, $enrollmentDate, $schoolId)
    {
        $this->gradeId = $gradeId;
        $this->enrollmentDate = $enrollmentDate;
        $this->schoolId = $schoolId;
    }

    public function model(array $row)
    {
       
        try {
            // Validación de campos requeridos
            $requiredFields = ['nombres', 'apellidos', 'dni'];
            foreach ($requiredFields as $field) {
                if (!array_key_exists($field, $row)) {
                    $this->errorCount++;
                    $this->errors[] = "Fila " . ($this->getRowNumber() ?? 'N/A') . ": Falta la columna '$field'";
                    return null;
                }

                if (empty(trim($row[$field]))) {
                    $this->errorCount++;
                    $this->errors[] = "Fila " . ($this->getRowNumber() ?? 'N/A') . ": El campo '$field' está vacío";
                    return null;
                }
            }

            // Validar o generar email
                $providedEmail = trim($row['email'] ?? '');
                $isEmailValid = filter_var($providedEmail, FILTER_VALIDATE_EMAIL);

                // Si es válido y no existe en la base de datos, úsalo. Si no, genera uno.
                if ($isEmailValid && !User::where('email', $providedEmail)->exists()) {
                    $email = $providedEmail;
                } else {
                    $email = $this->generateUniqueEmail($row);
                }

            // Verificar si el usuario ya existe por DNI
            $user = User::where('dni', $row['dni'])->where('school_id', $this->schoolId)->first();

            if (!$user) {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => trim($row['nombres']),
                    'lastname' => trim($row['apellidos']),
                    'dni' => trim($row['dni']),
                    'email' => $email,
                    'phone' => $row['telefono'] ?? null,
                    'cellphone' => $row['celular'] ?? null,
                    'address' => $row['direccion'] ?? null,
                    'status' => 1,
                    'school_id' => $this->schoolId,
                    'password' => Hash::make(trim($row['dni'])),
                ]);

                $user->assignRole('ESTUDIANTE');
            }

            // Verificar si ya tiene un registro de estudiante
            if ($user->student) {
                $this->errorCount++;
                $this->errors[] = "Fila " . ($this->getRowNumber() ?? '?') . ": El estudiante con DNI {$row['dni']} ya existe";
                return null;
            }

            // Crear registro de estudiante
            $student = Student::create([
                'user_id' => $user->id,
                'current_grade_id' => $this->gradeId,
                'enrollment_date' => $this->enrollmentDate,
                'academic_status' => 1,
                'additional_info' => json_encode([
                    'imported_from' => 'Excel Import',
                    'import_date' => now()->toDateTimeString(),
                    'row_data' => $row,
                    'generated_email' => $email !== ($row['email'] ?? null), // true si se generó
                ]),
            ]);

            $this->successCount++;
            return $student;

        } catch (\Illuminate\Database\QueryException $e) {
            $this->errorCount++;
            $this->errors[] = "Fila " . ($this->getRowNumber() ?? '?') . ": Error de base de datos - " . $e->getMessage();
            return null;
        } catch (\Exception $e) {
            $this->errorCount++;
            $this->errors[] = "Fila " . ($this->getRowNumber() ?? '?') . ": Error inesperado - " . $e->getMessage();
            return null;
        }
    }

    protected function generateUniqueEmail(array $row): string
    {
        $baseEmail = Str::lower(
            Str::slug($row['nombres']) . '.' . 
            Str::slug($row['apellidos']) . 
            '@' . Str::slug($this->schoolId) . '.edu'
        );

        if (!User::where('email', $baseEmail)->exists()) {
            return $baseEmail;
        }

        // Si el email base existe, agregar sufijo numérico
        $counter = 1;
        do {
            $uniqueEmail = Str::lower(
                Str::slug($row['nombres']) . '.' . 
                Str::slug($row['apellidos']) . 
                $counter . 
                '@' . Str::slug($this->schoolId) . '.edu'
            );
            $counter++;
        } while (User::where('email', $uniqueEmail)->exists());

        return $uniqueEmail;
    }

    public function rules(): array
    {
        return [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => [
                'required',
                'string',
                'max:10',
                function ($attribute, $value, $fail) {
                    if (User::where('dni', $value)->where('school_id', $this->schoolId)->exists()) {
                        $fail('El DNI ya está registrado en esta escuela.');
                    }
                },
            ],
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:10',
            'celular' => 'nullable|string|max:10',
            'direccion' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombres.required' => 'El campo nombres es requerido',
            'apellidos.required' => 'El campo apellidos es requerido',
            'dni.required' => 'El campo DNI es requerido',
        ];
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function headingRow(): int
    {
        return 1; // La primera fila son los encabezados
    }

    public function startRow(): int
    {
        return 2; // Empieza a leer datos desde la segunda fila
    }
}