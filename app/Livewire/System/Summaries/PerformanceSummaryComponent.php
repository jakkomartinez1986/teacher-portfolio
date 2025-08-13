<?php

namespace App\Livewire\System\Summaries;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\System\Student\Student;
use App\Models\Settings\Trimester\Trimester;
use App\Models\Settings\School\GradingSetting;
use App\Models\System\Teacher\PerformanceSummary;

class PerformanceSummaryComponent extends Component
{
    public $subjectId, $gradeId, $trimesterId;
    public $students = [];
    public $formData = [];
    public $percentages = [];
    public $grados = [];
    public $asignaturas = [];
    public $trimestres = [];
    public $selectedGrade, $selectedSubject, $selectedTrimester;

    public function mount()
    {
        $this->loadSelectionData();
    }

    protected function loadSelectionData()
    {
        $teachingGradeIds = Auth::user()->classSchedules()
                            ->pluck('grade_id')
                            ->unique()
                            ->toArray();    
        $teachingSubjectsIds = Auth::user()->classSchedules()
                            ->pluck('subject_id')
                            ->unique()
                            ->toArray();       
                            
        $this->grados = Grade::whereIn('id', $teachingGradeIds)
                    ->orderBy('grade_name')
                    ->get() ?? [];
                    
        $this->asignaturas = Subject::whereIn('id', $teachingSubjectsIds)
                    ->orderBy('subject_name')
                    ->get() ?? [];
                    
        $this->trimestres = Trimester::where('status', 1)->orderBy('id')->get() ?? [];
    }

    public function loadData()
    {
        $this->validate([
            'gradeId' => 'required|exists:grades,id',
            'subjectId' => 'required|exists:subjects,id',
            'trimesterId' => 'required|exists:trimesters,id',
        ]);
        
        $this->selectedGrade = Grade::find($this->gradeId);
        $this->selectedSubject = Subject::find($this->subjectId);
        $this->selectedTrimester = Trimester::find($this->trimesterId);

        // $this->students = User::whereHas('grades', fn($q) =>
        //     $q->where('current_grade_id', $this->gradeId))->orderBy('lastname')->get() ?? [];

        $this->students = Student::where('current_grade_id', $this->selectedGrade->id)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select([
                'students.*',
                'users.name',
                'users.lastname',
                'users.email',
                'users.dni',
                'users.phone',
                'users.cellphone',
                'users.address',
                'users.profile_photo_path'
            ])
            ->orderBy('users.lastname')
            ->orderBy('users.name')
            ->get();

        $config = GradingSetting::where('status', true)->latest()->first();

        $this->percentages = [
            'formative' => $config->formative_percentage ?? 70,
            'exam' => $config->exam_percentage ?? 15,
            'project' => $config->project_percentage ?? 15,
        ];

        $this->formData = [];
        
        foreach ($this->students as $student) {
            $existing = PerformanceSummary::where([
                'subject_id' => $this->subjectId,
                'grade_id' => $this->gradeId,
                'trimester_id' => $this->trimesterId,
                'student_id' => $student->id,
            ])->first();
            
        $this->formData[$student->id] = [
                'formative_scores' => $existing->formative_scores ?? array_fill(0, 5, null),
                'final_evaluation' => $existing->final_evaluation ?? null,
                'integral_project' => $existing->integral_project ?? null,
            ];
        }
    }

    public function updated($field)
    {
        // $this->validateOnly($field, $this->rules());
    }

    public function rules()
    {
        $rules = [];

        foreach ($this->formData as $studentId => $data) {
            $rules["formData.$studentId.formative_scores.*"] = 'nullable|numeric|min:0|max:10';
            $rules["formData.$studentId.final_evaluation"] = 'nullable|numeric|min:0|max:10';
            $rules["formData.$studentId.integral_project"] = 'nullable|numeric|min:0|max:10';
        }

        return $rules;
    }

    public function calcularNotas($studentId)
    {
        if (!isset($this->formData[$studentId])) {
            return [
                'formative_average' => 0,
                'summative_average' => 0,
                'final_grade' => 0,
                'grade_scale' => 'NA',
            ];
        }

        $form = $this->formData[$studentId];

        $formative = collect($form['formative_scores'] ?? [])->filter(fn($n) => is_numeric($n));
        $avgFormative = $formative->count() ? round($formative->avg(), 2) : 0;

        $exam = $form['final_evaluation'] ?? 0;
        $project = $form['integral_project'] ?? 0;
        $avgSumativo = round(($exam + $project) / 2, 2);

        $notaFinal = round(
            ($avgFormative * ($this->percentages['formative'] ?? 0) / 100) +
            ($exam * ($this->percentages['exam'] ?? 0) / 100) +
            ($project * ($this->percentages['project'] ?? 0) / 100), 
            2
        );

        $escala = match (true) {
            $notaFinal >= 9 => 'DA',
            $notaFinal >= 7 => 'AA',
            $notaFinal >= 5 => 'PA',
            default => 'NA'
        };

        return [
            'formative_average' => $avgFormative,
            'summative_average' => $avgSumativo,
            'final_grade' => $notaFinal,
            'grade_scale' => $escala,
        ];
    }

    public function save()
    {
        $this->validate();

        foreach ($this->formData as $studentId => $data) {
            $notas = $this->calcularNotas($studentId);

            PerformanceSummary::updateOrCreate(
                [
                    'subject_id' => $this->subjectId,
                    'grade_id' => $this->gradeId,
                    'trimester_id' => $this->trimesterId,
                    'student_id' => $studentId,
                ],
                [
                    'formative_scores' => $data['formative_scores'] ?? [],
                    'integral_project' => $data['integral_project'] ?? null,
                    'final_evaluation' => $data['final_evaluation'] ?? null,
                    'formative_average' => $notas['formative_average'],
                    'summative_average' => $notas['summative_average'],
                    'final_grade' => $notas['final_grade'],
                    'grade_scale' => $notas['grade_scale'],
                ]
            );
        }

        session()->flash('message', 'Notas guardadas correctamente.');
    }

    public function render()
    {
        $this->loadSelectionData();
        return view('livewire.system.summaries.performance-summary-component', [
            'grados' => $this->grados,
            'asignaturas' => $this->asignaturas,
            'trimestres' => $this->trimestres,
        ]);
    }
   
}