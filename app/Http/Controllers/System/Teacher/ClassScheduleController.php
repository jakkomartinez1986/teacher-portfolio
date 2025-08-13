<?php

namespace App\Http\Controllers\System\Teacher;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\Settings\Trimester\Trimester;
use App\Models\System\Teacher\ClassSchedule;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // Aplica el middleware para roles
        $this->middleware('role:ADMIN|SUPER-ADMIN|TUTOR')->only(['create', 'store', 'edit', 'update']);
        
        // Aplica el middleware para permisos
        $this->middleware('permission:crear-classschedule')->only(['create', 'store']);
        $this->middleware('permission:editar-classschedule')->only(['edit', 'update']);
        // $this->middleware('permission:borrar-user')->only('destroy');
    }
    public function index()
    {
        $grado = null; // Valor por defecto
      
        $tutoria = ClassSchedule::where('teacher_id', Auth::id())
            ->whereHas('subject', function ($q) {
            $q->where('subject_name', 'like', '%Acompañamiento integral en el aula%');
            })
            ->first();
     

        if ($tutoria) {
        // Paso 2: Si es tutor, obtener el horario completo de su grado
            $gradeId = $tutoria->grade_id;
            $grado=$tutoria->classroom;

            $gradohorario = ClassSchedule::with(['teacher', 'subject'])
            ->where('grade_id', $gradeId)
            ->get()
            ->sortBy([
            fn($a, $b) => strcmp($a->day, $b->day),
            fn($a, $b) => strcmp($a->start_time, $b->start_time),
            ]);
        } else {
        // Paso alternativo: no es tutor, mostrar solo su propio horario
            $gradohorario = ClassSchedule::with(['teacher', 'subject'])
            ->where('teacher_id', Auth::id())
            ->get()
            ->sortBy([
            fn($a, $b) => strcmp($a->day, $b->day),
            fn($a, $b) => strcmp($a->start_time, $b->start_time),
            ]);
        }
        //dd($gradohorario);
        return view('pages.system.academic.schedule.index',compact('gradohorario','grado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::role('DOCENTE')->get();
        $subjects = Subject::all();
        $grades = Grade::all();
        $trimesters = Trimester::active()->get();

        return view('pages.system.academic.schedule.create-edit', compact(
            'teachers', 'subjects', 'grades', 'trimesters'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            // 'trimester_id' => 'required|exists:trimesters,id',
            'day' => 'required|in:LUNES,MARTES,MIÉRCOLES,JUEVES,VIERNES,SÁBADO',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'classroom' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        ClassSchedule::create($validated);

        return redirect()->route('academic.class-schedules.index')
            ->with('success', 'Horario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSchedule $classSchedule)
    {
        return view('pages.system.academic.schedule.show', compact('classSchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSchedule $classSchedule)
    {
        $teachers = User::role('Docente')->get();
        $subjects = Subject::all();
        $grades = Grade::all();
        $trimesters = Trimester::active()->get();

        return view('pages.system.academic.schedule.create-edit', compact(
            'classSchedule', 'teachers', 'subjects', 'grades', 'trimesters'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            // 'trimester_id' => 'required|exists:trimesters,id',
            'day' => 'required|in:LUNES,MARTES,MIÉRCOLES,JUEVES,VIERNES,SÁBADO',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'classroom' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $classSchedule->update($validated);

        return redirect()->route('academic.class-schedules.index')
            ->with('success', 'Horario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSchedule $classSchedule)
    {
        // $classSchedule->delete();
        
        // return redirect()->route('academic.class-schedules.index')
        //     ->with('success', 'Horario eliminado exitosamente.');
    }
}
