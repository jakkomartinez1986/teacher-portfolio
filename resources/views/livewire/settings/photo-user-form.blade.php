<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
   use WithFileUploads;
 
//  [Validate('image|max:1024')] // 1MB Max
 public $photo;
//  [Validate('image|max:1024')] // 1MB Max
 public $signature;

}; ?>

<section class="mt-10 space-y-6">   
    {{-- <div class="mt-5 w-full max-w-lg"> --}}
        <form wire:submit="save" class="my-6 w-full space-y-6">
            <flux:avatar circle badge badge:color="green" src="{{ asset(Auth::user()->defaultUserPhotoUrl() )}}"/>
            <flux:input type="file" wire:model="photo" label="Foto Perfil"/>
            <flux:avatar badge badge:color="green" badge:circle src="{{asset( Auth::user()->defaultSignaturePhotoUrl()) }}" />
            <flux:input type="file" wire:model="signature" label="Foto Firma"/>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save Images') }}</flux:button>
                </div>
        
                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    {{-- </div>       --}}

</section>

