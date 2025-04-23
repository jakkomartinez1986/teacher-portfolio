<x-layouts.app :title="isset($user) ? __('Editar Usuario') : __('Crear Usuario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.users.index') }}">Usuarios</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ isset($user) ? 'Editar' : 'Crear' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="flex flex-col gap-6 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                {{ isset($user) ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
            </h2>
            
            <form method="POST" 
                  action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" 
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Columna izquierda -->
                    <div class="space-y-6">
                        <!-- Nombres -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="name" type="text" name="name" 
                                       value="{{ old('name', $user->name ?? '') }}" required
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="lastname" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="lastname" type="text" name="lastname" 
                                       value="{{ old('lastname', $user->lastname ?? '') }}" required
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('lastname')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Cédula -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="dni" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Cédula <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="dni" type="text" name="dni" 
                                       value="{{ old('dni', $user->dni ?? '') }}" required
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('dni')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Email <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="email" type="email" name="email" 
                                       value="{{ old('email', $user->email ?? '') }}" required
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contraseña -->
                        {{-- <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    {{ isset($user) ? 'Nueva Contraseña' : 'Contraseña' }} 
                                    @if(!isset($user)) <span class="text-red-500">*</span> @endif
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="password" type="password" name="password" 
                                       {{ !isset($user) ? 'required' : '' }}
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div> --}}

                        <!-- Confirmar Contraseña -->
                        {{-- <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Confirmar {{ isset($user) ? 'Nueva ' : '' }}Contraseña
                                    @if(!isset($user)) <span class="text-red-500">*</span> @endif
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="password_confirmation" type="password" name="password_confirmation" 
                                       {{ !isset($user) ? 'required' : '' }}
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                            </div>
                        </div> --}}
                    </div>

                    <!-- Columna derecha -->
                    <div class="space-y-6">
                        <!-- Teléfono -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="phone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Teléfono
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="phone" type="text" name="phone" 
                                       value="{{ old('phone', $user->phone ?? '') }}"
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Celular -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="cellphone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Celular
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="cellphone" type="text" name="cellphone" 
                                       value="{{ old('cellphone', $user->cellphone ?? '') }}"
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('cellphone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="address" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Dirección
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <input id="address" type="text" name="address" 
                                       value="{{ old('address', $user->address ?? '') }}"
                                       class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Escuela -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="school_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Escuela
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <select id="school_id" name="school_id"
                                        class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                    <option value="">Seleccione una escuela</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $user->school_id ?? '') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name_school }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <select id="status" name="status" required
                                        class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                    <option value="1" {{ old('status', $user->status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('status', $user->status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Roles <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <div class="md:col-span-8">
                                <div class="space-y-2">
                                    @foreach($roles as $role)
                                        <div class="flex items-center">
                                            <input id="role-{{ $role->id }}" type="checkbox" name="roles[]" 
                                                   value="{{ $role->id }}"
                                                   class="rounded border-neutral-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700"
                                                   {{ in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                            <label for="role-{{ $role->id }}" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('roles')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fotos -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Foto de perfil -->
                    <div class="space-y-2">
                        <label for="profile_photo_path" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Foto de Perfil
                        </label>
                        <input type="file" id="profile_photo_path" name="profile_photo_path" 
                               accept="image/*" 
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('profile_photo_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if(isset($user) && $user->profile_photo_path)
                            <div class="mt-2 flex items-center gap-4">
                                <img src="{{ $user->defaultUserPhotoUrl() }}" 
                                     alt="Foto actual" 
                                     class="h-16 w-16 rounded-full object-cover">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="remove_profile_photo" value="1">
                                    <span>Eliminar foto actual</span>
                                </label>
                            </div>
                        @endif
                    </div>

                    <!-- Firma -->
                    <div class="space-y-2">
                        <label for="signature_photo_path" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Firma
                        </label>
                        <input type="file" id="signature_photo_path" name="signature_photo_path" 
                               accept="image/*" 
                               class="block w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                        @error('signature_photo_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if(isset($user) && $user->signature_photo_path)
                            <div class="mt-2 flex items-center gap-4">
                                <img src="{{ $user->defaultSignaturePhotoUrl() }}" 
                                     alt="Firma actual" 
                                     class="h-16 w-32 object-contain">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="remove_signature_photo" value="1">
                                    <span>Eliminar firma actual</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Botones con Flux UI -->
                <div class="flex space-x-4 mt-6 pt-4 justify-end">
                    <flux:button 
                        icon="arrow-uturn-left" 
                        href="{{ route('admin.users.index') }}"
                        variant="outline">
                        {{ __('Volver a la Lista') }}
                    </flux:button>
                    
                    <flux:button 
                        type="submit"
                        icon="{{ isset($user) ? 'check-circle' : 'plus' }}" 
                        color="primary"
                    >
                        {{ isset($user) ? __('Actualizar Usuario') : __('Crear Usuario') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>