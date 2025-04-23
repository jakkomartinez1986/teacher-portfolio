<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.roles.index') }}">Roles</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Crear Rol</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
           
            <form action="{{ route('admin.roles.store') }}" method="POST">
                 <!-- Mostrar errores -->
                 @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700 p-4">
                        <strong>¡Ups! Hay algunos errores:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
               
                @include('pages.manager.role.role-form', ['role' => $role ?? new \Spatie\Permission\Models\Role])
            </form>
        </div>
    </div>
   
</x-layouts.app>