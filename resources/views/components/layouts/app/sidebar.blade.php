@php
    // Definición de todos los grupos y enlaces posibles
    $allGroups = [
        'Plataforma' => [
            [
                'name' => 'Dashboard',
                'icon' => 'home',
                'label' => __('Dashboard'),
                'route' => route('dashboard'),
                'current' => request()->routeIs('dashboard'),
                'badge'=> 'Dev',
                'color'=> 'teal',
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE', 'TUTOR'] // Roles permitidos
            ],
        ],
        'Docente' => [
            [
                'name' => 'Portafolio',
                'icon' => 'home',
                'label' => __('Portafolio'),
                'route' => route('documents.documents.index'),
                'current' => request()->routeIs('documents.documents.*'),
                'badge'=> 'Dev',
                'color'=> 'fuchsia',
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE']
            ],
            [
                'name' => 'Horario',
                'icon' => 'clock',
                'label' => __('Horario'),
                'route' => route('academic.teacher-schedule.index'),
                'current' => request()->routeIs('academic.teacher-schedule.*'),
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE']
            ],
            [
                'name' => 'Asistencia',
                'icon' => 'numbered-list',
                'label' => __('Asistencia'),
                'route' => route('academic.attendance.index'),
                'current' => request()->routeIs('academic.attendance.*'),
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE']
            ],
             [
                'name' => 'Panel Asist. Dcnt',
                'icon' => 'numbered-list',
                'label' => __('Panel Asist. Dcnt'),
                'route' => route('academic.attendance-dashboard-teacher'),
                'current' => request()->routeIs('academic.attendance-dashboard-teacher.*'),
                'badge'=> 'Anlz',
                'color'=> 'rose',
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE']
            ],
            [
                'name' => 'Notas',
                'icon' => 'pencil-square',
                'label' => __('Notas'),
                'route' => route('academic.summary.index'),
                'current' => request()->routeIs('academic.summary.*'),
                'badge'=> 'Anlz',
                'color'=> 'rose',
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE']
            ],
             [
                'name' => 'Panel Notas',
                'icon' => 'pencil-square',
                'label' => __('Panel Notas'),
                'route' => route('dashboard'),
                'current' => request()->routeIs('dashboard.*'),
                'badge'=> 'Anlz',
                'color'=> 'rose',
                'roles' => ['SUPER-ADMIN','ADMIN', 'DOCENTE']
            ],
        ],
        'Tutor' => [
            [
                'name' => 'Horario Grado',
                'icon' => 'calendar-days',
                'label' => __('Horario Grado'),
                'route' => route('academic.class-schedules.index'),
                'current' => request()->routeIs('academic.class-schedules.*'),
                'badge'=> 'Dev',
                'color'=> 'fuchsia',
                 'roles' => ['SUPER-ADMIN','ADMIN', 'TUTOR']
            ],
            [
                'name' => 'Estudiantes',
                'icon' => 'user-plus',
                'label' => __('Estudiantes'),
                'route' => route('students.students.index'),
                'current' => request()->routeIs('students.students.*'),
                'badge'=> 'Anlz',
                'color'=> 'rose',
                 'roles' => ['SUPER-ADMIN','ADMIN', 'TUTOR']
            ],
            [
                'name' => 'Panel Asist. Tutor',
                'icon' => 'numbered-list',
                'label' => __('Panel Asist. Tutor'),
                'route' => route('academic.attendance-dashboard-tutor'),
                'current' => request()->routeIs('academic.attendance-dashboard-tutor.*'),
                'badge'=> 'Anlz',
                'color'=> 'rose',
                'roles' => ['SUPER-ADMIN','ADMIN', 'TUTOR']
            ],
             [
                'name' => 'Panel Notas Tutor',
                'icon' => 'numbered-list',
                'label' => __('Panel Notas Tutor'),
                'route' => route('dashboard'),
                'current' => request()->routeIs('dashboard.*'),
                'badge'=> 'Anlz',
                'color'=> 'rose',
                'roles' => ['SUPER-ADMIN','ADMIN', 'TUTOR']
            ],
        ],
        'Configuración' => [
            [
                'name' => 'Colegio',
                'icon' => 'building-library',
                'label' => __('Colegio'),
                'route' => route('settings.schools.index'),
                'current' => request()->routeIs('settings.schools.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Año Gestion',
                'icon' => 'calendar-date-range',
                'label' => __('Año Gestion'),
                'route' => route('settings.years.index'),
                'current' => request()->routeIs('settings.years.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Conf. Calificaciones',
                'icon' => 'information-circle',
                'label' => __('Conf. Calificaciones'),
                'route' => route('settings.grading-settings.index'),
                'current' => request()->routeIs('settings.grading-settings.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Periodos',
                'icon' => 'bars-arrow-up',
                'label' => __('Periodos'),
                'route' => route('settings.trimesters.index'),
                'current' => request()->routeIs('settings.trimesters.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Jornadas',
                'icon' => 'archive-box',
                'label' => __('Jornadas'),
                'route' => route('settings.shifts.index'),
                'current' => request()->routeIs('settings.shifts.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Nivel',
                'icon' => 'adjustments-vertical',
                'label' => __('Nivel'),
                'route' => route('settings.nivels.index'),
                'current' => request()->routeIs('settings.nivels.*'),
                 'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Grado',
                'icon' => 'academic-cap',
                'label' => __('Grado'),
                'route' => route('settings.grades.index'),
                'current' => request()->routeIs('settings.grades.*'),
                 'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Area',
                'icon' => 'building-office-2',
                'label' => __('Area'),
                'route' => route('settings.areas.index'),
                'current' => request()->routeIs('settings.areas.*'),
                 'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Asignatura',
                'icon' => 'bars-3-bottom-right',
                'label' => __('Asignatura'),
                'route' => route('settings.subjects.index'),
                'current' => request()->routeIs('settings.subjects.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
        ],
        'Configuración Docs' => [
            [
                'name' => 'Categoria Documentos',
                'icon' => 'document-chart-bar',
                'label' => __('Categoria Documentos'),
                'route' => route('settings.document-categories.index'),
                'current' => request()->routeIs('settings.document-categories.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Tipo Documentos',
                'icon' => 'document-chart-bar',
                'label' => __('Tipo Documentos'),
                'route' => route('settings.document-types.index'),
                'current' => request()->routeIs('settings.document-types.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
        ],
        'Calendario' => [
            [
                'name' => 'Calendario',
                'icon' => 'calendar',
                'label' => __('Calendario Escolar'),
                'route' => route('settings.calendar-days.index'),
                'current' => request()->routeIs('settings.calendar-days.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],           
        ],
        'Usuarios' => [
            [
                'name' => 'Usuarios',
                'icon' => 'users',
                'label' => __('Usuarios'),
                'route' => route('admin.users.index'),
                'current' => request()->routeIs('admin.users.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Roles',
                'icon' => 'lock-closed',
                'label' => __('Roles'),
                'route' => route('admin.roles.index'),
                'current' => request()->routeIs('admin.roles.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
            [
                'name' => 'Permisos',
                'icon' => 'shield-check',
                'label' => __('Permisos'),
                'route' => route('admin.permissions.index'),
                'current' => request()->routeIs('admin.permissions.*'),
                'roles' => ['SUPER-ADMIN','ADMIN']
            ],
        ],
    ];

    // Obtener roles del usuario actual (ajusta según tu implementación de roles)
    $userRoles = auth()->user()->roles->pluck('name')->toArray() ?? [];
    
    // Filtrar grupos y enlaces según los roles del usuario
    $filteredGroups = [];
    
    foreach ($allGroups as $groupName => $links) {
        $filteredLinks = [];
        
        foreach ($links as $link) {
            // Si no se especifican roles o el usuario tiene alguno de los roles requeridos
            if (!isset($link['roles']) || count(array_intersect($userRoles, $link['roles'])) > 0) {
                $filteredLinks[] = $link;
            }
        }
        
        // Solo agregar el grupo si tiene links visibles
        if (!empty($filteredLinks)) {
            $filteredGroups[$groupName] = $filteredLinks;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @foreach ($filteredGroups as $group => $links)
                    {{-- Versión móvil (dropdown/accordion) --}}
                    <flux:navlist.group expandable :heading="$group" class="lg:hidden">
                        @foreach ($links as $link)
                            @if (isset($link['badge']))
                                <flux:navlist.item :icon="$link['icon']" :href="$link['route']" :current="$link['current']" wire:navigate>
                                    <flux:badge variant="outline" size="sm" :color="$link['color']" class="absolute top-0 right-0 mt-1 mr-1">
                                        {{ $link['badge'] }}
                                    </flux:badge>
                                    {{ $link['label'] }}
                                </flux:navlist.item>
                            @else
                                <flux:navlist.item :icon="$link['icon']" :href="$link['route']" :current="$link['current']" wire:navigate>
                                    {{ $link['label'] }}
                                </flux:navlist.item>
                            @endif
                        @endforeach
                    </flux:navlist.group>
            
                    {{-- Versión escritorio (grid) --}}
                    <flux:navlist.group :heading="$group" class="hidden lg:grid">
                        @foreach ($links as $link)
                            @if (isset($link['badge']))
                                <flux:navlist.item :icon="$link['icon']" :href="$link['route']" :current="$link['current']" wire:navigate>
                                    <flux:badge variant="outline" size="sm" :color="$link['color']" class="absolute top-0 right-0 mt-1 mr-1">
                                        {{ $link['badge'] }}
                                    </flux:badge>
                                    {{ $link['label'] }}
                                </flux:navlist.item>
                            @else
                                <flux:navlist.item :icon="$link['icon']" :href="$link['route']" :current="$link['current']" wire:navigate>
                                    {{ $link['label'] }}
                                </flux:navlist.item>
                            @endif
                        @endforeach
                    </flux:navlist.group>
                @endforeach
            </flux:navlist>
            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
