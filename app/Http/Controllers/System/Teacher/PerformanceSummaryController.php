<?php

namespace App\Http\Controllers\System\Teacher;

use App\Http\Controllers\Controller;
use App\Models\System\Teacher\PerformanceSummary;
use Illuminate\Http\Request;

class PerformanceSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  public function __construct()
    // {
    //     // Aplica el middleware para roles
    //     $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update']);
        
    //     // Aplica el middleware para permisos
    //     $this->middleware('permission:crear-academicgrading')->only(['create', 'store']);
    //     $this->middleware('permission:editar-academicgrading')->only(['edit', 'update']);
    //     // $this->middleware('permission:borrar-user')->only('destroy');
    // }
    public function index()
    {
         return view('pages.system.academic.summary.index');
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
    public function show(PerformanceSummary $performanceSummary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceSummary $performanceSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceSummary $performanceSummary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceSummary $performanceSummary)
    {
        //
    }
}
