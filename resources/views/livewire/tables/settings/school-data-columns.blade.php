<thead>
    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('id')" href="#" class="hover:underline flex items-center gap-1">
                ID 
                @include('components.app-components.sort-icon', ['field' => 'id'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('name_school')" href="#" class="hover:underline flex items-center gap-1">
                Nombre 
                @include('components.app-components.sort-icon', ['field' => 'name_school'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('distrit')" href="#" class="hover:underline flex items-center gap-1">
                Distrito 
                @include('components.app-components.sort-icon', ['field' => 'distrit'])
            </a>
        </th>
        <th class="px-4 py-3">Direccion</th>
        <th class="px-4 py-3">Contactos</th>      
        <th class="px-4 py-3">Email</th>
        <th class="px-4 py-3">Sitio Web</th>
        <th class="px-4 py-3">Opciones</th>       
    </tr>
</thead>
<tbody class="text-sm ">
    @forelse($data as $item)
    {{-- @foreach($data as $item) --}}
    <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
        <td class="px-4 py-3">{{ $item->id }}</td>
    
        <td class="px-4 py-3 flex items-center gap-2">
            <flux:avatar src="{{ $item->defaultSchoolPhotoUrl() }}" size="xs" />
            <span class="max-md:hidden">{{ $item->name_school }}</span>
        </td>
        <td class="px-4 py-3">{{ $item->distrit}}</td>
        <td class="px-4 py-3">{{ $item->getAdressAttribute() }}</td>   
        <td class="px-4 py-3">{{ $item->phone}}</td>        
        <td class="px-4 py-3">
            @if($item->email)
                <a href="{{ $item->email }}" target="_blank" 
                    class="text-blue-600 dark:text-blue-400 hover:underline">
                    {{ $item->email }}
                </a>
            @else
                <span class="text-gray-400">No especificado</span>
            @endif
        </td>
        <td class="px-4 py-3">
            @if($item->website)
                <a href="{{ $item->website }}" target="_blank" 
                    class="text-blue-600 dark:text-blue-400 hover:underline">
                    {{ $item->website }}
                </a>
            @else
                <span class="text-gray-400">No especificado</span>
            @endif
        </td>    
           
        <td class="px-4 py-3">
            <div class="flex items-center space-x-2">
                <flux:dropdown position="bottom" align="end" offset="-15">
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                    <flux:menu>
                        <flux:menu.item icon="eye" size="sm" href="{{route('settings.schools.show', $item)}}">Ver</flux:menu.item>
                        @can('editar-'.$modelactive)
                            <flux:menu.item icon="pencil" size="sm" href="{{route('settings.schools.edit', $item->id)}}" >Editar</flux:menu.item>
                        @endcan
                        {{-- @can('borrar-'.$modelactive)
                             @if ( Auth::user()->id!=$item->id) 
                                <flux:menu.item wire:click.prevent="delete({{ $item->id }})" icon="trash" size="sm" variant="danger">Borrar</flux:menu.item>
                            @endif    
                        @endcan                             --}}
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
