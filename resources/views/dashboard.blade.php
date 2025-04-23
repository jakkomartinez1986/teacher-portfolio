<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#">Home</flux:breadcrumbs.item>
            {{-- <flux:breadcrumbs.item href="#">Blog</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Post</flux:breadcrumbs.item> --}}
        </flux:breadcrumbs>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        {{-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            
           
        </div> --}}
        <div class="relative h-full flex gap-4">
            <!-- Columna izquierda (vacía por ahora o para otro componente después) -->
            <div class="flex-1 border border-neutral-200 dark:border-neutral-700 rounded-xl p-4">
                <!-- Aquí puedes poner otro componente si deseas -->
                {{-- <livewire:system.teacher.teacher-schedule /> --}}
            </div>
        
            <!-- Columna derecha con el componente centrado -->
            <div class="flex-1 flex items-center justify-center border border-neutral-200 dark:border-neutral-700 rounded-xl p-4">
                {{-- <livewire:system.teacher.teacher-schedule /> --}}
            </div>
        </div>
    </div>
</x-layouts.app>
