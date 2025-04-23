<div >
    <section class="max-w-7xl mx-auto">
        <div class="mt-2 rounded-lg">
            <div class="mb-4 flex flex-wrap gap-4 items-center">
                @can('editar-'.$modelactive) 
                    <flux:button icon="plus"  wire:click="openNewModal"> {{ __('Nuevo') }}</flux:button>
                @endcan
                <flux:button icon="arrow-down-tray"  wire:click="export">{{ __('Exportar') }}</flux:button>
            </div>
        </div>

        <div class="mb-4 flex flex-col md:flex-row md:justify-between gap-4 items-center">
            <div class="w-full md:w-1/3">
                <flux:select wire:model.live="perPage" placeholder="Selecciona cantidad...">
                    @foreach ($perPageOptions as $option)
                        <flux:select.option value="{{ $option }}">
                            {{ $option }} por página
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>           
            <div class="w-full md:w-2/3">
                <flux:input 
                    wire:model.live.debounce.250ms="search"                    
                    placeholder="Buscar..." 
                    icon="magnifying-glass"
                />
            </div>
        </div>
    </section>
 
    <section class="max-w-7xl mx-auto">
        <div class="p-4 space-y-4 overflow-x-auto rounded-lg shadow relative">
            <table class="min-w-full border-separate border-spacing-y-2 text-left ">
                @include($view)
            </table>
        </div>

        <div class="mt-4 p-4 space-y-4">
            {{ $data->links() }}
        </div>
    </section>
</div>

<script>
    document.addEventListener('livewire:initialized', function () {
        @this.on('swal', (event) => {
            const data = event;
            Swal.fire({
                icon: data[0]['icon'],
                title: data[0]['title'],
                text: data[0]['text'],
            });
        });

        Livewire.on('delete-prompt', (event) => {
            const data = event;
            const id = data[0]['id'];

            Swal.fire({
                icon: data[0]['icon'],
                title: data[0]['title'],
                text: data[0]['text'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteConfirmed', { id });
                }
            });
        });
    });
</script>
