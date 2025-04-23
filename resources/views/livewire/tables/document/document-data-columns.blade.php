
<thead>
    <tr class="text-xs border-b uppercase font-semibold tracking-wider">
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('id')" href="#" class="hover:underline flex items-center gap-1">
                ID 
                @include('components.app-components.sort-icon', ['field' => 'id'])
            </a>
        </th>       
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('title')" href="#" class="hover:underline flex items-center gap-1">
                Título
                @include('components.app-components.sort-icon', ['field' => 'title'])
            </a>
        </th>
        <th class="px-4 py-3">Tipo</th>     
        <th class="px-4 py-3">
            <a wire:click.prevent="sortBy('description')" href="#" class="hover:underline flex items-center gap-1">
                Descripcion
                @include('components.app-components.sort-icon', ['field' => 'description'])
            </a>
        </th>         
        <th class="px-4 py-3">Estado</th>
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
            <td class="px-4 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                            {{ $item->title }}
                        </div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">
                            {{ $item->creator->name }}
                        </div>
                    </div>
                </div>
            </td> 
            <td class="px-4 py-3">
                <div class="text-sm text-neutral-900 dark:text-neutral-100">{{ $item->type->name }}</div>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ $item->year->year_name }}</div>
            </td>            
            <td class="px-4 py-3">{{ $item->description }}</td>  
              
            <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                    {{ $item->status === 'DRAFT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $item->status === 'PENDING_REVIEW' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $item->status === 'APPROVED' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $item->status === 'REJECTED' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $item->status === 'ARCHIVED' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ __($item->status) }}
                </span>
            </td>
            <td class="px-4 py-3">{{ $item->created_at }}</td>     
             
            <td class="px-4 py-3">
                <div class="flex items-center space-x-2">
                    <flux:dropdown position="bottom" align="end" offset="-15">
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                        <flux:menu>
                            <flux:menu.item icon="eye" size="sm" href="{{route('documents.documents.show', $item)}}">Ver</flux:menu.item>
                            @can('editar-'.$modelactive)
                                <flux:menu.item icon="pencil" size="sm" href="{{route('documents.documents.edit', $item->id)}}" >Editar</flux:menu.item>
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
