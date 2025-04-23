<thead>
    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('id')" href="#" class="hover:underline flex items-center gap-1">
                ID 
                @include('components.app-components.sort-icon', ['field' => 'id'])
            </a>
        </th>
        <th class="px-4 py-3">Area</th> 
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('subject_name')" href="#" class="hover:underline flex items-center gap-1">
                Asignatura
                @include('components.app-components.sort-icon', ['field' => 'subject_name'])
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
            <td class="px-4 py-3">{{ $item->area->area_name }}</td>        
            <td class="px-4 py-3">{{ $item->subject_name }}</td>     
            <td class="px-4 py-3">
                <div class="flex items-center space-x-2">
                    <flux:dropdown position="bottom" align="end" offset="-15">
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                        <flux:menu>
                            <flux:menu.item icon="eye" size="sm" href="{{route('settings.subjects.show', $item)}}">Ver</flux:menu.item>
                            @can('editar-'.$modelactive)
                                <flux:menu.item icon="pencil" size="sm" href="{{route('settings.subjects.edit', $item->id)}}" >Editar</flux:menu.item>
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
