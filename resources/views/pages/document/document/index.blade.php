<x-layouts.app :title="__('Gestión de Documentos')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Documentos</flux:breadcrumbs.item>   
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

        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    Gestión de Documentos
                </h2>
            </div>
            
            @can('create-document')
                <flux:button 
                    icon="plus" 
                    href="{{ route('documents.documents.create') }}" 
                    color="primary"
                >
                    Nuevo Documento
                </flux:button>
            @endcan
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            {{-- @livewire('document.document-table') --}}
            @livewire('data-table', [
                'model' => \App\Models\Document\Document::class,
                'view' => 'livewire.tables.document.document-data-columns',
                'showRoute'=>'documents.documents.show',
                'editRoute'=>'documents.documents.edit',
                'newRoute'=>'documents.documents.create',
            ])           
        </div>
    </div>
</x-layouts.app>