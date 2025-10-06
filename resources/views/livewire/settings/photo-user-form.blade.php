<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use WithFileUploads;

    public $photo;
    public $signature;

    public function save()
    {
        try {
            // Validar los archivos (ambas opcionales)
            $this->validate([
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);

            $user = Auth::user();

            // Guardar la foto de perfil solo si se subió
            if ($this->photo) {
                $photoPath = $this->photo->store('profile-photos', 'public');
                $user->update(['profile_photo_path' => $photoPath]);
            }

            // Guardar la firma solo si se subió
            if ($this->signature) {
                $signaturePath = $this->signature->store('signatures', 'public');
                $user->update(['signature_photo_path' => $signaturePath]);
            }

            // Emitir evento para mostrar mensaje de éxito
            $this->dispatch('profile-updated', message: 'Images saved successfully!');
            
            // Limpiar los campos de archivo
            $this->reset(['photo', 'signature']);

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error saving images: ' . $e->getMessage());
        }
    }
};
?>
<section class="mt-10 space-y-6">   
    <form wire:submit="save" class="my-6 w-full space-y-6">
        <!-- Foto de perfil -->
        <div class="flex items-center gap-4 mb-4">
            <flux:avatar circle badge badge:color="green" src="{{ asset(Auth::user()->defaultUserPhotoUrl()) }}"/>
            <div>
                <p class="text-sm font-medium">{{__('Current Profile Photo')}}</p>
            </div>
        </div>
        
        <flux:input type="file" wire:model="photo" label="New Profile Photo" required/>
        
        <!-- Firma actual (opcional) -->
        <div class="flex items-center gap-4 mb-4">
            <flux:avatar badge badge:color="green" badge:circle src="{{ asset(Auth::user()->defaultSignaturePhotoUrl()) }}" />
            <div>
                <p class="text-sm font-medium">{{__('Current Signature')}} <span class="text-gray-500">{{__('Optional')}}</span></p>
            </div>
        </div>
        
        <flux:input type="file" wire:model="signature" label="New Signature (Optional)"/>
        
        <div class="flex items-center gap-4">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Save Images') }}
            </flux:button>
            
            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>