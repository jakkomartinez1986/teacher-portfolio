<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new class extends Component {
    use WithFileUploads; // Asegúrate de incluir esto
    public string $name = '';
    
    public string $lastname = '';
    public string $dni = '';
    public string $phone = '';
    public string $cellphone = '';
    public string $address = '';

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->lastname = Auth::user()->lastname;
        $this->dni = Auth::user()->dni;
        $this->phone = Auth::user()->phone;
        $this->cellphone = Auth::user()->cellphone;
        $this->address = Auth::user()->address;

        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'regex:/[0-9]{9}/', 'size:10','unique:' .  Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'regex:/[0-9]{9}/', 'size:10'],
            'cellphone' => ['required', 'regex:/[0-9]{9}/', 'size:10'],
            'address' => ['required', 'string', 'max:255'],
           
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);
        $validated['name'] = strtoupper($validated['name']);
        $validated['lastname'] = strtoupper($validated['lastname']);
        $validated['address'] = strtoupper($validated['address']);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

  
    <x-settings.layout-foto :heading="__('Subir Imagen')" :subheading="__('Subir imagen de Perfil y de Firma')">        
        <livewire:settings.photo-user-form />
    </x-settings.layout-foto>
    <flux:separator variant="subtle" class="my-8" />

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">        
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />
            <flux:input wire:model="lastname" :label="__('Lastname')" type="text" required autofocus autocomplete="Lastname" />
            <flux:input wire:model="dni" :label="__('Cedula')" type="text" required autofocus autocomplete="dni" />
            <flux:input wire:model="phone" :label="__('Tel. Convencional')" type="text" required autofocus autocomplete="phone" />
            <flux:input wire:model="cellphone" :label="__('Tel. Celular')" type="text" required autofocus autocomplete="cellphone" />
            <flux:input wire:model="address" :label="__('Direccion')" type="text" required autofocus autocomplete="address" />
          

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
        <flux:separator variant="subtle" class="my-8" />

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
