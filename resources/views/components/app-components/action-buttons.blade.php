<td class="text-xs   row-action--icon">
    <a href="{{ route($showRoute, $item->id) }}" class="fa fa-lg fa-eye text-green-500"></a>
    <a href="{{ route($editRoute, $item->id) }}" class="fa fa-lg fa-pen text-yellow-500"></a>
    <button wire:click.prevent="delete({{ $item->id }})" class="fa fa-lg fa-trash text-red-500"></button>
</td> 

