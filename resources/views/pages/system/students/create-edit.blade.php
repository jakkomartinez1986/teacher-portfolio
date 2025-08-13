<x-layouts.app :title="__($mode === 'create' ? 'Crear Estudiante' : 'Editar Estudiante')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Inicio</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('students.students.index') }}">Estudiantes</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $mode === 'create' ? 'Crear' : 'Editar' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                    {{ $mode === 'create' ? 'Crear Nuevo Estudiante' : 'Editar Estudiante' }}
                </h2>
                <flux:badge :color="$mode === 'create' ? 'blue' : 'green'">
                    {{ $mode === 'create' ? 'Nuevo' : 'Edición' }}
                </flux:badge>
            </div>

            <form method="POST" action="{{ $mode === 'create' ? route('students.students.store') : route('students.students.update', $student) }}" enctype="multipart/form-data">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Columna izquierda -->
                    <div class="space-y-6">
                        <!-- Foto de perfil -->
                        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                            <h3 class="flex items-center gap-2 text-lg font-medium text-neutral-700 dark:text-neutral-300">
                                <flux:icon name="camera" class="h-5 w-5" />
                                Foto de Perfil
                            </h3>

                            <div class="flex flex-col items-center gap-4 sm:flex-row">
                                <div class="flex-shrink-0">
                                    <div class="relative h-32 w-32">
                                        <img id="profile-photo-preview"
                                            src="{{ $mode === 'edit' && $student->user->profile_photo_path 
                                                ? asset('storage/' . $student->user->profile_photo_path)
                                                : 'https://ui-avatars.com/api/?name=NV&background=6875F5&color=f5f5f5' }}"
                                            alt="Foto de perfil"
                                            class="h-full w-full rounded-full border-2 border-neutral-200 object-cover dark:border-neutral-600">
                                        <div id="remove-photo-btn" class="absolute -right-2 -top-2 hidden">
                                            <flux:button type="button" size="xs" color="red" variant="outline" class="rounded-full p-1">
                                                <flux:icon name="x-mark" class="h-4 w-4" />
                                            </flux:button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <flux:input
                                        type="file"
                                        name="profile_photo"
                                        id="profile_photo"
                                        label="Subir foto"
                                        accept="image/*"
                                        :error="$errors->first('profile_photo')"
                                    />
                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                        Formatos: JPEG, PNG, JPG, GIF. Máx. 2MB
                                    </p>

                                    @if($mode === 'edit' && $student->user->profile_photo_path)
                                        <div class="mt-2 flex items-center">
                                            <flux:checkbox
                                                name="remove_profile_photo"
                                                id="remove_profile_photo"
                                                label="Eliminar foto actual"
                                            />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Información Personal -->
                        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                            <h3 class="flex items-center gap-2 text-lg font-medium text-neutral-700 dark:text-neutral-300">
                                <flux:icon name="user" class="h-5 w-5" />
                                Información Personal
                            </h3>

                            <flux:input label="DNI" name="dni" id="dni" :value="old('dni', $student->user->dni ?? '')" required placeholder="Ingrese el DNI" :error="$errors->first('dni')" />
                            <flux:input label="Nombres" name="name" id="name" :value="old('name', $student->user->name ?? '')" required placeholder="Ingrese los nombres" :error="$errors->first('name')" />
                            <flux:input label="Apellidos" name="lastname" id="lastname" :value="old('lastname', $student->user->lastname ?? '')" required placeholder="Ingrese los apellidos" :error="$errors->first('lastname')" />
                            <flux:input type="email" label="Correo Electrónico" name="email" id="email" :value="old('email', $student->user->email ?? '')" placeholder="Ingrese el correo electrónico" :error="$errors->first('email')" />
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="space-y-6">
                        <!-- Información Académica -->
                        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                            <h3 class="flex items-center gap-2 text-lg font-medium text-neutral-700 dark:text-neutral-300">
                                <flux:icon name="academic-cap" class="h-5 w-5" />
                                Información Académica
                            </h3>

                            <flux:select label="Grado Actual" name="current_grade_id" id="current_grade_id" required :error="$errors->first('current_grade_id')">
                                <option value="">Seleccione un grado</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('current_grade_id', $student->current_grade_id ?? '') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->grade_name }} {{ $grade->section }}
                                    </option>
                                @endforeach
                            </flux:select>

                            <flux:input type="date" label="Fecha de Matrícula" name="enrollment_date" id="enrollment_date" :value="old('enrollment_date', isset($student) ? $student->enrollment_date->format('Y-m-d') : date('Y-m-d'))" required :error="$errors->first('enrollment_date')" />

                            <flux:select label="Estado Académico" name="academic_status" id="academic_status" required :error="$errors->first('academic_status')">
                                <option value="1" {{ old('academic_status', $student->academic_status ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('academic_status', $student->academic_status ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                                <option value="2" {{ old('academic_status', $student->academic_status ?? 1) == 2 ? 'selected' : '' }}>Graduado</option>
                                <option value="3" {{ old('academic_status', $student->academic_status ?? 1) == 3 ? 'selected' : '' }}>Suspendido</option>
                            </flux:select>

                            <flux:textarea label="Información Adicional" name="additional_info" id="additional_info" rows="3" placeholder="Información adicional sobre el estudiante" :error="$errors->first('additional_info')">{{ old('additional_info', $student->additional_info ?? '') }}</flux:textarea>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                            <h3 class="flex items-center gap-2 text-lg font-medium text-neutral-700 dark:text-neutral-300">
                                <flux:icon name="phone" class="h-5 w-5" />
                                Información de Contacto
                            </h3>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <flux:input label="Teléfono" name="phone" id="phone" :value="old('phone', $student->user->phone ?? '')" placeholder="Ingrese el teléfono" :error="$errors->first('phone')" />
                                <flux:input label="Celular" name="cellphone" id="cellphone" :value="old('cellphone', $student->user->cellphone ?? '')" placeholder="Ingrese el celular" :error="$errors->first('cellphone')" />
                                <div class="sm:col-span-2">
                                    <flux:input label="Dirección" name="address" id="address" :value="old('address', $student->user->address ?? '')" placeholder="Ingrese la dirección" :error="$errors->first('address')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-end gap-3">
                    <flux:button type="button" color="gray" variant="outline" href="{{ route('students.students.index') }}" tag="a">Cancelar</flux:button>
                    <flux:button type="submit" color="blue">{{ $mode === 'create' ? 'Crear Estudiante' : 'Actualizar Estudiante' }}</flux:button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('profile_photo')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile-photo-preview').src = event.target.result;
                    document.getElementById('remove-photo-btn')?.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        @if($mode === 'edit' && $student->user->profile_photo_path)
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('remove-photo-btn')?.classList.remove('hidden');
            });
        @endif

        document.getElementById('remove-photo-btn')?.addEventListener('click', function() {
            document.getElementById('profile-photo-preview').src = "{{ asset('images/default-profile.png') }}";
            document.getElementById('profile_photo').value = '';
            document.getElementById('remove-photo-btn').classList.add('hidden');

            @if($mode === 'edit')
                document.getElementById('remove_profile_photo').checked = true;
            @endif
        });
    </script>
    @endpush
</x-layouts.app>
