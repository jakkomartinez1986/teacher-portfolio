<?php

namespace App\Http\Controllers\Settings\Calendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\School\Year;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings\Trimester\Trimester;
use App\Models\Settings\Calendar\CalendarDay;
use App\Imports\Settings\Calendar\CalendarDaysImport;

class CalendarDayController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:ADMIN|SUPER-ADMIN')->only(['create', 'store', 'edit', 'update', 'destroy', 'importForm', 'import']);
        $this->middleware('permission:crear-calendarday')->only(['create', 'store']);
        $this->middleware('permission:editar-calendarday')->only(['edit', 'update']);
        $this->middleware('permission:borrar-calendarday')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $selectedYear = $request->input('year_id');
        $selectedTrimester = $request->input('trimester_id');
        $selectedMonth = $request->input('month', now()->month);
        $selectedYearParam = $request->input('year', now()->year);

        // Fecha actual para el calendario
        $currentMonth = \Carbon\Carbon::create($selectedYearParam, $selectedMonth, 1);

        $query = CalendarDay::with(['year', 'trimester'])
            ->orderBy('date', 'asc');

        if ($selectedYear) {
            $query->where('year_id', $selectedYear);
        }

        if ($selectedTrimester) {
            $query->where('trimester_id', $selectedTrimester);
        }

        // Obtener todos los días del mes actual para el calendario
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $calendarDays = $query->whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        // Eventos del mes para el panel lateral
        $monthEvents = $calendarDays->whereNotNull('activity')->sortBy('date');

        // Próximos eventos (7 días)
        $upcomingEvents = CalendarDay::where('date', '>=', now())
                                    ->where('date', '<=', now()->addDays(7))
                                    ->whereNotNull('activity')
                                    ->orderBy('date')
                                    ->get();

        return view('pages.settings.calendar-days.index', [
            'calendarDays' => $calendarDays,
            'monthEvents' => $monthEvents,
            'upcomingEvents' => $upcomingEvents,
            'years' => Year::orderBy('year_name', 'desc')->get(),
            'trimesters' => Trimester::all(),
            'selectedYear' => $selectedYear,
            'selectedTrimester' => $selectedTrimester,
            'selectedMonth' => $selectedMonth,
            'currentMonth' => $currentMonth,
            'currentDate' => now()->format('Y-m-d')
        ]);
    }

    public static function getEventColorStatic($period, $isHoliday = false)
    {
        if ($isHoliday) {
            return '#ef4444'; // Rojo para feriados
        }

        $colors = [
            'PRIMERO' => '#6366f1',
            'SEGUNDO' => '#8b5cf6', 
            'TERCERO' => '#ec4899',
            'PRIMER TRIMESTRE' => '#6366f1',
            'SEGUNDO TRIMESTRE' => '#8b5cf6',
            'TERCER TRIMESTRE' => '#ec4899',
            'PRIMER TRIMESTRE' => '#6366f1',
            'SEGUNDO TRIMESTRE' => '#8b5cf6', 
            'TERCER TRIMESTRE' => '#ec4899'
        ];
        
        $periodUpper = strtoupper(trim($period));
        return $colors[$periodUpper] ?? '#3b82f6'; // Azul por defecto
    }
    protected function formatCalendarDay($day)
    {
        $isHoliday = $day->activity && stripos($day->activity, 'feriado') !== false;
        
        return [
            'id' => $day->id,
            'title' => $day->activity ?? "Día lectivo",
            'start' => $day->date->format('Y-m-d'),
            'allDay' => true,
            'extendedProps' => [
                'period' => $day->period,
                'trimester' => $day->trimester->trimester_name ?? '',
                'year' => $day->year->year_name ?? '',
                'day_number' => $day->day_number,
                'week' => $day->week,
                'has_activity' => !empty($day->activity),
                'is_holiday' => $isHoliday
            ],
            'color' => $this->getEventColor($day->period, $isHoliday),
            'className' => !empty($day->activity) ? 'has-activity' : 'no-activity',
            'url' => route('settings.calendar-days.edit', $day)
        ];
    }

    protected function getEventColor($period, $isHoliday = false)
    {
        if ($isHoliday) {
            return '#ef4444'; // Rojo para feriados
        }

        $colors = [
            'Primer Trimestre' => '#6366f1', // 
            'Segundo Trimestre' => '#8b5cf6', // 
            'Tercer Trimestre' => '#ec4899'  // 
        ];
        
        return $colors[strtoupper($period)] ?? '#3b82f6'; // Azul por defecto
    }

    public function create(Request $request)
    {
        $date = $request->input('date');
        
        return view('pages.settings.calendar-days.create-edit', [
            'years' => Year::orderBy('year_name', 'desc')->get(),
            'trimesters' => Trimester::all(),
            'preselectedDate' => $date ? \Carbon\Carbon::parse($date) : null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'period' => 'required|string|max:50',
            'date' => 'required|date|unique:calendar_days,date',
            'month_name' => 'required|string|max:20',
            'day_name' => 'required|string|max:20',
            'week' => 'required|integer|min:1|max:53',
            'day_number' => 'required|integer|min:1|max:31',
            'activity' => 'nullable|string|max:255',
        ]);

        CalendarDay::create($validated);

        return redirect()->route('settings.calendar-days.index')
            ->with('success', 'Día calendario creado exitosamente.');
    }

    public function edit(CalendarDay $calendarDay)
    {
        return view('pages.settings.calendar-days.create-edit', [
            'calendarDay' => $calendarDay,
            'years' => Year::orderBy('year_name', 'desc')->get(),
            'trimesters' => Trimester::all()
        ]);
    }

    public function update(Request $request, CalendarDay $calendarDay)
    {
        $validated = $request->validate([
            'year_id' => 'required|exists:years,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'period' => 'required|string|max:50',
            'date' => 'required|date|unique:calendar_days,date,'.$calendarDay->id,
            'month_name' => 'required|string|max:20',
            'day_name' => 'required|string|max:20',
            'week' => 'required|integer|min:1|max:53',
            'day_number' => 'required|integer|min:1|max:31',
            'activity' => 'nullable|string|max:255',
        ]);

        $calendarDay->update($validated);

        return redirect()->route('settings.calendar-days.index')
            ->with('success', 'Día calendario actualizado exitosamente.');
    }

    public function destroy(CalendarDay $calendarDay)
    {
        $calendarDay->delete();

        return redirect()->route('settings.calendar-days.index')
            ->with('success', 'Día calendario eliminado exitosamente.');
    }

    public function importForm()
    {
        return view('pages.settings.calendar-days.import', [
            'years' => Year::orderBy('year_name', 'desc')->get()
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'year_id' => 'required|exists:years,id'
        ]);

        $import = new CalendarDaysImport($request->year_id);
        
        try {
            Excel::import($import, $request->file('file'));
            
            if ($import->failures()->isNotEmpty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with([
                        'import_errors' => $import->failures(),
                        'success_message' => 'Importación parcialmente exitosa. Algunas filas no se procesaron.'
                    ]);
            }
            
            return redirect()
                ->route('settings.calendar-days.index')
                ->with('success', 'Días calendario importados exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error al importar: " . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('storage/templates/calendar_days_import_template.xlsx'));
    }

}