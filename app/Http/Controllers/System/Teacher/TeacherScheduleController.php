<?php

namespace App\Http\Controllers\System\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\Trimester\Trimester;
use App\Models\System\Teacher\ClassSchedule;

class TeacherScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = ClassSchedule::with(['subject', 'grade'])//, 'trimester'
        ->where('teacher_id', Auth::id())
        // ->currentTrimester()
        ->orderBy('day')
        ->orderBy('start_time')
        ->get()
        ->groupBy('day');
     
    return view('pages.system.teacher.schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        $grades = Grade::all();
        // $trimesters = Trimester::active()->get();

        return view('pages.system.teacher.schedule.create-edit', compact(
            'subjects', 'grades'
        ));
        // , 'trimesters'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            // 'trimester_id' => 'required|exists:trimesters,id',
            'day' => 'required|in:LUNES,MARTES,MIÉRCOLES,JUEVES,VIERNES,SÁBADO',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'classroom' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        // Asignar automáticamente el docente logueado
        $validated['teacher_id'] = Auth::id();
        $validated['is_active'] = true;

        ClassSchedule::create($validated);

        return redirect()->route('academic.teacher-schedule.index')
            ->with('success', 'Horario creado exitosamente.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSchedule $classSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSchedule $classSchedule)
    {
        $subjects = Subject::all();
        $grades = Grade::all();
        // $trimesters = Trimester::active()->get();

        return view('pages.system.teacher.schedule.create-edit', compact(
            'subjects', 'grades'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSchedule $classSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSchedule $classSchedule)
    {
        //
    }
}
