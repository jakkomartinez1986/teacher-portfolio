
<thead>
    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('id')" href="#" class="hover:underline flex items-center gap-1">
                ID 
                @include('components.app-components.sort-icon', ['field' => 'id'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('name')" href="#" class="hover:underline flex items-center gap-1">
                Usuario 
                @include('components.app-components.sort-icon', ['field' => 'name'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('phone')" href="#" class="hover:underline flex items-center gap-1">
                Contactos 
                @include('components.app-components.sort-icon', ['field' => 'phone'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('adrress')" href="#" class="hover:underline flex items-center gap-1">
                Direccion 
                @include('components.app-components.sort-icon', ['field' => 'adrress'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('email')" href="#" class="hover:underline flex items-center gap-1">
                Email 
                @include('components.app-components.sort-icon', ['field' => 'email'])
            </a>
        </th>
        <th class="px-4 py-3">Cargo</th>
        @can('editar-'.$modelactive)
            <th class="px-4 py-3">Alta</th>
        @endcan
        <th class="px-4 py-3">Opciones</th>
    </tr>
</thead>
<tbody class="text-sm ">
    {{-- @foreach($data as $item) --}}
    @forelse($data as $item)
        <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
            <td class="px-4 py-3">{{ $item->id }}</td>
        
            <td class="px-4 py-3 flex items-center gap-2">
                <flux:avatar src="{{ $item->defaultUserPhotoUrl() }}" size="xs" />
                <span class="max-md:hidden">{{ $item->getFullNameAttribute() }}</span>
            </td>
        
            <td class="px-4 py-3">{{ $item->getContactsAttribute() }}</td>
            <td class="px-4 py-3">{{ $item->address }}</td>
            <td class="px-4 py-3">{{ $item->email }}</td>
        
            <td class="px-4 py-3">
                @if ($item->getRoleNames()->count() > 0)
                    @foreach($item->getRoleNames() as $rolNombre)
                        <flux:badge color="emerald" size="sm">{{ $rolNombre }}</flux:badge>  
                    @endforeach
                @else
                    <flux:badge color="rose" size="sm">No Asignado</flux:badge>                
                @endif
            </td>
            @can('editar-'.$modelactive) 
            <td class="px-4 py-3 flex items-center gap-2">
                                 

                    @php
                        $color = $item->status ? 'emerald' : 'rose';
                        $icon = $item->status ? 'user-plus' : 'user-minus';
                    @endphp
                    <flux:badge 
                        as="button" 
                        wire:click="toggleActivation({{ $item->id }})"  
                        color="{{ $color }}" 
                        icon="{{ $icon }}" 
                        size="sm">
                        {{ $item->estadoUsuario() }}
                    </flux:badge>
            </td>
            @endcan
            <td class="px-4 py-3">
                <div class="flex items-center space-x-2">
                    <flux:dropdown position="bottom" align="end" offset="-15">
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                        <flux:menu>
                            <flux:menu.item icon="eye" size="sm" href="{{route('admin.users.show', $item)}}">Ver</flux:menu.item>
                            @can('editar-'.$modelactive)
                                <flux:menu.item icon="pencil" size="sm" href="{{route('admin.users.edit', $item->id)}}" >Editar</flux:menu.item>
                            @endcan
                            @can('borrar-'.$modelactive)
                                @if ( Auth::user()->id!=$item->id) 
                                    <flux:menu.item wire:click.prevent="delete({{ $item->id }})" icon="trash" size="sm" variant="danger">Borrar</flux:menu.item>
                                @endif    
                            @endcan                            
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </td>
        </tr>    
    {{-- @endforeach --}}


    @empty
            <tr>
                <td class="px-4 py-3">{{'No Existen Datos de: '.$modelactive}}</td>
            </tr>
    @endforelse
</tbody>
