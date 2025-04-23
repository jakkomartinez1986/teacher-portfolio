
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
                Nombre 
                @include('components.app-components.sort-icon', ['field' => 'name'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('guard_name')" href="#" class="hover:underline flex items-center gap-1">
                Guard 
                @include('components.app-components.sort-icon', ['field' => 'guard_name'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('created_at')" href="#" class="hover:underline flex items-center gap-1">
                Creado 
                @include('components.app-components.sort-icon', ['field' => 'created_at'])
            </a>
        </th>
         <th class="px-4 py-3">Opciones</th>
    </tr>
</thead>
<tbody class="text-sm ">
    @forelse($data as $item)
    {{-- @foreach($data as $item) --}}
        <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
            <td class="px-4 py-3">{{ $item->id }}</td>           
            <td class="px-4 py-3">{{ $item->name }}</td>
            <td class="px-4 py-3">{{ $item->guard_name }}</td>   
            <td class="px-4 py-3">{{ $item->created_at }}</td>       
            <td class="px-4 py-3">
                <div class="flex items-center space-x-2">
                    <flux:dropdown position="bottom" align="end" offset="-15">
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                        <flux:menu>
                            <flux:menu.item icon="eye" size="sm" href="{{route('admin.roles.show', $item)}}">Ver</flux:menu.item>
                            @can('editar-'.$modelactive)
                                <flux:menu.item icon="pencil" size="sm" href="{{route('admin.roles.edit', $item->id)}}" >Editar</flux:menu.item>
                            @endcan
                            @can('borrar-'.$modelactive)
                            @unless(in_array($item->name, ['SUPER-ADMIN', 'ADMIN']))
                                <flux:menu.item 
                                    wire:click.prevent="delete({{ $item->id }})" 
                                    icon="trash" 
                                    size="sm" 
                                    variant="danger">
                                    Borrar
                                </flux:menu.item>
                            @endunless                         
                            
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
