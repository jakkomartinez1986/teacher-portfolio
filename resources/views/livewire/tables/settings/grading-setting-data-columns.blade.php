<thead>
    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('id')" href="#" class="hover:underline flex items-center gap-1">
                ID 
                @include('components.app-components.sort-icon', ['field' => 'id'])
            </a>
        </th>
        <th class="px-4 py-3"> Año Academico </th> 
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('formative_percentage')" href="#" class="hover:underline flex items-center gap-1">
                % Formativa
                @include('components.app-components.sort-icon', ['field' => 'formative_percentage'])
            </a>
        </th> 
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('summative_percentage')" href="#" class="hover:underline flex items-center gap-1">
                % Sumativa
                @include('components.app-components.sort-icon', ['field' => 'summative_percentage'])
            </a>
        </th>        
        <th class="px-4 py-3">Estado</th>         
        <th class="px-4 py-3">Opciones</th>        
    </tr>
</thead>
<tbody class="text-sm ">
    @forelse($data as $item)
    {{-- @foreach($data as $item) --}}
    <tr class="hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200 border-b">
        <td class="px-4 py-3">{{ $item->id }}</td>
    
        <td class="px-4 py-3">{{ $item->year->year_name }} </td>
        <td class="px-4 py-3">{{ $item->formative_percentage }}%</td>
        <td class="px-4 py-3">
            <!-- Sumativa - Texto principal -->
            <div class="font-medium mb-1"> {{ $item->summative_percentage }}%</div>
            
            <!-- Proyecto y Examen - Badges pequeños -->
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-2 py-0.5 text-[0.7rem] font-medium rounded-full bg-blue-100 text-blue-800">
                    Proyecto: {{ $item->project_percentage }}%
                </span>
                <span class="inline-flex items-center px-2 py-0.5 text-[0.7rem] font-medium rounded-full bg-green-100 text-green-800">
                    Examen: {{ $item->exam_percentage }}%
                </span>
            </div>
        </td>
        <td class="px-4 py-3">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $item->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $item->status == 1 ? 'Activo' : 'Inactivo' }}
            </span>
        </td>
             
        <td class="px-4 py-3">
            <div class="flex items-center space-x-2">
                <flux:dropdown position="bottom" align="end" offset="-15">
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                    <flux:menu>
                        <flux:menu.item icon="eye" size="sm" href="{{route('settings.grading-settings.show', $item)}}">Ver</flux:menu.item>
                        @can('editar-'.$modelactive)
                            <flux:menu.item icon="pencil" size="sm" href="{{route('settings.grading-settings.edit', $item->id)}}" >Editar</flux:menu.item>
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
