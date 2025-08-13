<style>
    .sticky {
        position: sticky;
    }
    .left-0 {
        left: 0;
    }
    .z-10 {
        z-index: 10;
    }
    
    
    /* Opcional: para el contenedor de la tabla */
    .table-container {
        overflow-x: auto;
        width: 100%;
    }
</style>
<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Horario</flux:breadcrumbs.item>   
        </flux:breadcrumbs>      

        @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-500 text-white p-4 rounded-md shadow-md flex justify-between items-center">
                    <div>{{ session('success') }}</div>
                    <button @click="show = false" class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    Horario:
                    <span class="block text-base font-normal text-gray-600 dark:text-gray-400 mt-1">
                         {{$grado}}
                    </span>
                </h1>
                  @php
                    $dias = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES'];

                    // Determina duración del bloque
                    $duracionBloque = str_contains($grado, 'BT Técnico') ? 40 : 45;

                    // Genera bloques de clase a partir del horario original
                    $bloques = collect();

                    foreach ($gradohorario as $clase) {
                        $inicio = $clase->start_time->copy();
                        $fin = $clase->end_time->copy();

                        while ($inicio < $fin) {
                            $bloques->push([
                                'day' => $clase->day,
                                'start_time' => $inicio->copy(),
                                'end_time' => $inicio->copy()->addMinutes($duracionBloque),
                                'subject' => $clase->subject->subject_name,
                                'teacher' => $clase->teacher->getFullNameAttribute(),
                            ]);
                            $inicio->addMinutes($duracionBloque);
                        }
                    }

                    // Extrae las horas únicas y ordénalas
                    $horas = $bloques->pluck('start_time')->unique()->sort();
                @endphp
            <div class="table-container">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full border border-collapse text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-xs border-b uppercase font-semibold tracking-wider bg-gray-100 dark:bg-gray-800">
                                <th class="border px-2 py-2 sticky left-0 bg-white dark:bg-gray-900 z-10">Hora</th>
                                @foreach ($dias as $dia)
                                    <th class="border px-2 py-2 capitalize text-center">{{ $dia }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>                         
                            @foreach ($horas as $hora)
                                <tr>
                                    <td class="sticky left-0 z-10 border px-2 py-2 text-center font-medium">
                                        {{ $hora->format('H:i') }} - {{ $hora->copy()->addMinutes($duracionBloque)->format('H:i') }}
                                    </td>

                                    @foreach ($dias as $dia)
                                        @php
                                            $bloque = $bloques->first(function ($b) use ($dia, $hora) {
                                                return $b['day'] === $dia && $b['start_time']->format('H:i') === $hora->format('H:i');
                                            });
                                        @endphp

                                        <td class="border px-2 py-2 text-center">
                                            @if ($bloque)
                                                <div class="font-semibold">{{ $bloque['subject'] }}</div>
                                                <div class="text-xs text-gray-600">{{ $bloque['teacher'] }}</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            </div>
        </div>
    </div>
</x-layouts.app>