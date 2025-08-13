<?php

namespace App\Http\Controllers\System\Attendance;

use App\Http\Controllers\Controller;
use App\Models\System\Attendance\Attendance;
use Illuminate\Http\Request;

class AttendanceDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return view('pages.system.attendance.teacher-dashboard');
       
    }
   public function teacherdashboard()
    {
        //
        return view('pages.system.attendance.teacher-dashboard');
       
    }
     public function teacherincidentdashboard()
    {
        //
        return view('pages.system.attendance.teacher-incident-dashboard');
       
    }
     public function tutordashboard()
    {
        //
        return view('pages.system.attendance.tutor-dashboard');
       
    }

    public function tutorincidentdashboard()
    {
        //
        return view('pages.system.attendance.tutor-incident-dashboard');
       
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
