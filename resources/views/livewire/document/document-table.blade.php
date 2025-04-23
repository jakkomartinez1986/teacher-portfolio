<div>
    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
        <!-- Filtros -->
        <div class="flex flex-col space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0">
            <!-- Búsqueda -->
            <div class="relative w-full sm:w-64">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input wire:model.debounce.300ms="search" type="text" 
                       class="block w-full rounded-md border-neutral-300 pl-10 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200" 
                       placeholder="Buscar documentos...">
            </div>
            
            <!-- Filtro por estado -->
            {{-- <select wire:model="status" 
                    class="rounded-md border-neutral-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                <option value="">Todos los estados</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select> --}}
            {{-- <select wire:model="status" 
                class="rounded-md border-neutral-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                    <option value="">Todos los estados</option>
                    @foreach($this->statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
            </select>
            <!-- Filtro por tipo -->
            <select wire:model="type" 
                    class="rounded-md border-neutral-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                <option value="">Todos los tipos</option>
                @foreach($types as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select> --}}
        </div>
        
        <!-- Items por página -->
        <div class="flex items-center space-x-2">
            <span class="text-sm text-neutral-600 dark:text-neutral-400">Mostrar</span>
            <select wire:model="perPage" 
                    class="rounded-md border-neutral-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
    
    <!-- Tabla -->
    {{-- <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                        <div class="flex items-center space-x-1">
                            <span>Título</span>
                            <button wire:click="sortBy('title')">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                        <div class="flex items-center space-x-1">
                            <span>Fecha</span>
                            <button wire:click="sortBy('created_at')">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                        Estado
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Acciones</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse($documents as $document)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ $document->title }}
                                    </div>
                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ $document->creator->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-neutral-900 dark:text-neutral-100">{{ $document->type->name }}</div>
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ $document->year->name }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">
                            {{ $document->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                {{ $document->status === 'DRAFT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $document->status === 'PENDING_REVIEW' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $document->status === 'APPROVED' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $document->status === 'REJECTED' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $document->status === 'ARCHIVED' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ __($document->status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('documents.show', $document) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                Ver
                            </a>
                            @if($document->status === 'DRAFT' && auth()->user()->can('update', $document))
                                <a href="{{ route('documents.edit', $document) }}" class="ml-4 text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    Editar
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            No se encontraron documentos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div> --}}
    
    <!-- Paginación -->
    {{-- <div class="mt-4">
        {{ $documents->links() }}
    </div> --}}
</div>