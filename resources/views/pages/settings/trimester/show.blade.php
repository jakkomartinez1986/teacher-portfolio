<x-layouts.app :title="__('Detalles del Periodo')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.trimesters.index') }}">Periodos</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Detalles</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Detalles del Periodo: {{ $trimester->trimester_name }}
                </h2>
                
                {{-- <div class="flex gap-2">
                    <a href="{{ route('settings.trimesters.edit', $trimester) }}" 
                       class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        Editar
                    </a>
                    <form action="{{ route('settings.trimesters.destroy', $trimester) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                onclick="return confirm('¿Estás seguro de eliminar este trimestre?')">
                            Eliminar
                        </button>
                    </form>
                </div> --}}
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información del Periodo</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Nombre</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->trimester_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Período</p>
                            <p class="text-neutral-800 dark:text-neutral-200">
                                {{ $trimester->start_date->format('d/m/Y') }} - {{ $trimester->end_date->format('d/m/Y') }}
                                <span class="text-sm text-neutral-500">({{ $trimester->start_date->diffInDays($trimester->end_date) }} días)</span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Estado</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $trimester->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $trimester->status_name }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-300">Información Adicional</h3>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Creado el</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Actualizado el</p>
                            <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($trimester->deleted_at)
                            <div>
                                <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Eliminado el</p>
                                <p class="text-neutral-800 dark:text-neutral-200">{{ $trimester->deleted_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>
             <!-- Botones de acción -->                           
             <div class="flex space-x-4 mt-6 pt-4 justify-end">                   
                <flux:button icon="arrow-uturn-left" href="{{ route('settings.trimesters.index') }}" > {{ __(' Volver a la Lista') }}</flux:button>
                    @can('editar-trimester')
                        <flux:button icon="pencil" href="{{ route('settings.trimesters.edit', $trimester->id) }}" > {{ __(' Editar Periodo') }}</flux:button>
                    @endcan
            </div>
        </div>
    </div>
</x-layouts.app>