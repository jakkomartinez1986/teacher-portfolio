<div>
    <div class="relative">
        <button @click="open = !open" class="flex items-center space-x-1 text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>
        
        <div x-show="open" @click.away="open = false" 
             class="absolute right-0 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-neutral-800 dark:ring-neutral-700 z-50"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95">
            <div class="p-4">
                <div class="flex items-center justify-between border-b border-neutral-200 pb-2 dark:border-neutral-700">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">Notificaciones</h3>
                    @if($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="text-sm text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                            Marcar todas como leídas
                        </button>
                    @endif
                </div>
                
                <div class="mt-2 max-h-96 overflow-y-auto">
                    @forelse($notifications as $notification)
                        <div class="border-b border-neutral-200 py-3 last:border-b-0 dark:border-neutral-700">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 
                                        {{ $notification['data']['type'] === 'success' ? 'text-green-500' : '' }}
                                        {{ $notification['data']['type'] === 'error' ? 'text-red-500' : '' }}
                                        {{ $notification['data']['type'] === 'warning' ? 'text-yellow-500' : '' }}
                                        {{ $notification['data']['type'] === 'info' ? 'text-blue-500' : '' }}" 
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($notification['data']['type'] === 'success')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @elseif($notification['data']['type'] === 'error')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @elseif($notification['data']['type'] === 'warning')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-neutral-900 dark:text-neutral-100">
                                        {{ $notification['data']['message'] }}
                                    </p>
                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                                @if(is_null($notification['read_at']))
                                    <button wire:click="markAsRead('{{ $notification['id'] }}')" 
                                            class="ml-2 text-neutral-400 hover:text-neutral-500 dark:text-neutral-500 dark:hover:text-neutral-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            No hay notificaciones
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-2 border-t border-neutral-200 pt-2 text-center dark:border-neutral-700">
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                        Ver todas las notificaciones
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>