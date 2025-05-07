<?php

namespace App\Http\Controllers\Settings\School;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Year;
use App\Models\Settings\School\GradingSetting;

class GradingSettingController extends Controller
{
    public function index()
    {
        return view('pages.settings.school.gradingsetting.index');
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $years = Year::where('status', 1)
            ->orderBy('year_name')
            ->get();
        
        return view('pages.settings.school.gradingsetting.create-edit', compact('years'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'year_id.required' => 'El año es requerido',
            'year_id.unique' => 'Ya existe una configuración de calificación para este año',
            'formative_percentage.required' => 'El porcentaje formativo es requerido',
            'summative_percentage.required' => 'El porcentaje sumativo es requerido',
            'exam_percentage.required' => 'El porcentaje de examen es requerido',
            'project_percentage.required' => 'El porcentaje de proyecto es requerido',
            'percentages.total' => 'La suma de los porcentajes formativos y sumativos debe ser exactamente 100%',
            'percentages.summative' => 'La suma de los porcentajes de examen y proyecto debe ser igual al porcentaje sumativo',
           ];
        
        $validated = $request->validate([
            'year_id' => [
                'required',
                'exists:years,id',
                Rule::unique('grading_settings')->where(function ($query) {
                    return $query->where('status', 1);
                })
            ],
            'formative_percentage' => 'required|numeric|min:0|max:100',
            'summative_percentage' => 'required|numeric|min:0|max:100',
            'exam_percentage' => 'required|numeric|min:0|max:100',
            'project_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ], $messages);

        // Validación adicional para los porcentajes
        $gradingSetting = new GradingSetting($validated);
        if (!$gradingSetting->validatePercentages()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'percentages' => 'Los porcentajes no suman correctamente. Formativo + Sumativo = 100% y Examen + Proyecto = Sumativo'
                ]);
        }

        GradingSetting::create($validated);
    
        return redirect()->route('settings.grading-settings.index')
            ->with('success', 'Configuración de calificación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GradingSetting $gradingSetting)
    {
        return view('pages.settings.school.gradingsetting.show', compact('gradingSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradingSetting $gradingSetting)
    {
        $years = Year::where('status', 1)
            ->orderBy('year_name')
            ->get();
            
        return view('pages.settings.school.gradingsetting.create-edit', compact('gradingSetting', 'years'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GradingSetting $gradingSetting)
    {
        $messages = [
            'year_id.required' => 'El año es requerido',
            'year_id.unique' => 'Ya existe una configuración de calificación para este año',
            'formative_percentage.required' => 'El porcentaje formativo es requerido',
            'summative_percentage.required' => 'El porcentaje sumativo es requerido',
            'exam_percentage.required' => 'El porcentaje de examen es requerido',
            'project_percentage.required' => 'El porcentaje de proyecto es requerido',
            'percentages.total' => 'La suma de los porcentajes formativos y sumativos debe ser exactamente 100%',
            'percentages.summative' => 'La suma de los porcentajes de examen y proyecto debe ser igual al porcentaje sumativo',
        ];
        
        $validated = $request->validate([
            'year_id' => [
                'required',
                'exists:years,id',
                Rule::unique('grading_settings')->ignore($gradingSetting->id)->where(function ($query) {
                    return $query->where('status', 1);
                })
            ],
            'formative_percentage' => 'required|numeric|min:0|max:100',
            'summative_percentage' => 'required|numeric|min:0|max:100',
            'exam_percentage' => 'required|numeric|min:0|max:100',
            'project_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ], $messages);

        // Validación adicional para los porcentajes
        $gradingSetting->fill($validated);
        if (!$gradingSetting->validatePercentages()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'percentages' => 'Los porcentajes no suman correctamente. Formativo + Sumativo = 100% y Examen + Proyecto = Sumativo'
                ]);
        }

        $gradingSetting->update($validated);
    
        return redirect()->route('settings.grading-settings.index')
            ->with('success', 'Configuración de calificación actualizada exitosamente.');
    }

}
