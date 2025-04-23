@props(['submit'])

<div {{ $attributes }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>
</div>
<div>
    <div>
        <form wire:submit.prevent="{{ $submit }}">
            <div class="{{ isset($actions)}}">
                <div>
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 p-6 sm:px-20 bg-white rounded-lg border shadow-md sm:p-6 dark:bg-gray-800 dark:border-gray-700">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
