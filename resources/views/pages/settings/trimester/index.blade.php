<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Periodos</flux:breadcrumbs.item>   
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
            @livewire('data-table', [
                'model' => \App\Models\Settings\Trimester\Trimester::class,
                'view' => 'livewire.tables.trimester.trimester-data-columns',
                'showRoute'=>'settings.trimesters.show',
                'editRoute'=>'settings.trimesters.edit',
                'newRoute'=>'settings.trimesters.create',
            ])      
        </div>
    </div>
</x-layouts.app>