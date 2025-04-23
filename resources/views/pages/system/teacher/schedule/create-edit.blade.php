<x-layouts.app :title="__('Agregar Horario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('academic.teacher-schedule.index') }}">Mi Horario</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Agregar</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
           
            <livewire:system.teacher.teacher-schedule-form />
           
        </div>
    </div>
</x-layouts.app>