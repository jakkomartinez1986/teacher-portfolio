<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Usuarios</flux:breadcrumbs.item>       
        </flux:breadcrumbs>      
        {{-- <div class="rounded-md bg-green-50 p-4 mb-4 relative">
            <!-- Contenido del mensaje -->
            <button onclick="this.parentElement.style.display='none'" class="absolute top-3 right-3 text-green-500 hover:text-green-600">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div> --}}
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
            @livewire('data-table', [
                        'model' => \App\Models\User::class,
                        'view' => 'livewire.tables.manager.user-data-columns',
                        'showRoute'=>'admin.users.show',
                        'editRoute'=>'admin.users.edit',
                        'newRoute'=>'admin.users.create',
                    ])           
        </div>
    </div>
</x-layouts.app>