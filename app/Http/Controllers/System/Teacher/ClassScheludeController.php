<?php

namespace App\Http\Controllers\System\Teacher;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\Area\Subject;
use App\Models\Settings\School\Grade;
use App\Models\Settings\Trimester\Trimester;
use App\Models\System\Teacher\ClassSchedule;

class ClassScheludeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.system.academic.schedule.index');
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
