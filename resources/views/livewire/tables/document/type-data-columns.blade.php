
<thead>
    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('id')" href="#" class="hover:underline flex items-center gap-1">
                ID 
                @include('components.app-components.sort-icon', ['field' => 'id'])
            </a>
        </th>
        <th class="px-4 py-3">Categoria</th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('name')" href="#" class="hover:underline flex items-center gap-1">
                Tipo de Documento
                @include('components.app-components.sort-icon', ['field' => 'name'])
            </a>
        </th>
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('description')" href="#" class="hover:underline flex items-center gap-1">
                Descripcion
                @include('components.app-components.sort-icon', ['field' => 'description'])
            </a>
        </th>
        <th class="px-4 py-3">Frecuencia</th>
        <th class="px-4 py-3">Firmas de :</th>
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
            <td class="px-4 py-3">{{ $item->category->name }}</td> 
            <td class="px-4 py-3">{{ $item->name }}</td>            
            <td class="px-4 py-3">{{ $item->description }}</td>   
            <td class="px-4 py-3">{{ $item->frequency }}</td>      
            <td class="px-4 py-3">
                @if($item->requires_director) Director-Area<br> @endif
                @if($item->requires_vice_principal) Vicerector<br> @endif
                @if($item->requires_principal) Rector<br> @endif
                @if($item->requires_dece) DECE<br> @endif
            </td>
            
            <td class="px-4 py-3">{{ $item->created_at }}</td>       
            <td class="px-4 py-3">
                <div class="flex items-center space-x-2">
                    <flux:dropdown position="bottom" align="end" offset="-15">
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                        <flux:menu>
                            <flux:menu.item icon="eye" size="sm" href="{{route('settings.document-types.show', $item)}}">Ver</flux:menu.item>
                            @can('editar-'.$modelactive)
                                <flux:menu.item icon="pencil" size="sm" href="{{route('settings.document-types.edit', $item->id)}}" >Editar</flux:menu.item>
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
