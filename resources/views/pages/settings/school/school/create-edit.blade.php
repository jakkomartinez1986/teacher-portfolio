<x-layouts.app :title="isset($school) ? __('Editar Escuela') : __('Crear Escuela')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('settings.schools.index') }}">Escuelas</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($school) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($school) ? 'Editar Escuela' : 'Crear Nueva Escuela' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($school) ? route('settings.schools.update', $school) : route('settings.schools.store') }}" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @if(isset($school))
                    @method('PUT')
                @endif

                <!-- Nombre de la Escuela -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="name_school" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="name_school" type="text" name="name_school" 
                               value="{{ old('name_school', $school->name_school ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('name_school')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Distrito -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="distrit" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Distrito <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="distrit" type="text" name="distrit" 
                               value="{{ old('distrit', $school->distrit ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('distrit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Localidad -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="location" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Localidad <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="location" type="text" name="location" 
                               value="{{ old('location', $school->location ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dirección -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="address" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Dirección <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="address" type="text" name="address" 
                               value="{{ old('address', $school->address ?? '') }}" required
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Teléfono
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="phone" type="text" name="phone" 
                               value="{{ old('phone', $school->phone ?? '') }}"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Email
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="email" type="email" name="email" 
                               value="{{ old('email', $school->email ?? '') }}"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sitio Web -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="website" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Sitio Web
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="website" type="url" name="website" 
                               value="{{ old('website', $school->website ?? '') }}"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Logo -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-3">
                        <label for="logo" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Logo
                        </label>
                    </div>
                    <div class="md:col-span-9">
                        <input id="logo" type="file" name="logo" accept="image/*"
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @if(isset($school) && $school->logo_path)
                            <div class="mt-2 flex items-center gap-4">
                                <img src="{{ $school->defaultSchoolPhotoUrl() }}" 
                                     alt="Logo actual" 
                                     class="h-16 w-16 rounded-md object-cover">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="remove_logo" value="1">
                                    <span>Eliminar logo actual</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

               <!-- Botones con Flux -->
            <div class="flex space-x-4 mt-6 pt-4 justify-end">
                <flux:button 
                    icon="arrow-uturn-left" 
                    href="{{ route('settings.schools.index') }}"
                    variant="outline"
                >
                    {{ __('Volver a la Lista') }}
                </flux:button>
                
                <flux:button 
                    type="submit"
                    icon="{{ isset($school) ? 'check-circle' : 'plus' }}" 
                    color="primary"
                >
                    {{ isset($school) ? __('Actualizar Escuela') : __('Crear Escuela') }}
                </flux:button>
            </div>
            </form>
        </div>
    </div>
</x-layouts.app>