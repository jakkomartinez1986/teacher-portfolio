<?php

use App\Models\User;
use App\Models\Settings\School\School;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $lastname = '';
    public string $dni = '';
    public string $phone = '';
    public string $cellphone = '';
    public string $address = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'dni' => ['required','ec_cedula', 'regex:/[0-9]{9}/', 'size:10','unique:' . User::class],
            'phone' => ['required', 'regex:/[0-9]{9}/', 'size:10'],
            'cellphone' => ['required', 'regex:/[0-9]{9}/', 'size:10'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['name'] = strtoupper($validated['name']);
        $validated['lastname'] = strtoupper($validated['lastname']);
        $validated['address'] = strtoupper($validated['address']);
        $validated['password'] = Hash::make($validated['password']);
         // Asignar escuela activa
         $school = School::where('status', 1)->first();
        if ($school) {
            $validated['school_id'] = $school->id;
        }

        // Establecer el status en 1 si el DNI es 1721583092
        if ($validated['dni'] === '1721583092') {
            $validated['status'] = 1; // Activar el usuario
        }
        event(new Registered(($user = User::create($validated))));
        if($validated['dni']=='1721583092'){
            $user->assignRole('SUPER-ADMIN');               
        }
       
        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
       <!-- Name -->
       <flux:input
        class="col-span-1"
        wire:model="name"
        :label="__('Name')"
        type="text"
        required
        autofocus
        autocomplete="name"
        :placeholder="__('Name')"
        />
       <!-- Last Name -->
        <flux:input
            class="col-span-1"
            wire:model="lastname"
            :label="__('Lastname')"
            type="text"
            required
            autofocus
            autocomplete="lastname"
            :placeholder="__('Lastname')"
            />
        <!-- Cedula -->
        <flux:input
            wire:model="dni"
            type="text"
            :label="__('Cedula')"
            required
            placeholder="99999999999"
            />
        <!-- direccion -->
        <flux:input
            wire:model="address"
            type="text"
            :label="__('Direccion')"
            required
            placeholder="Latacunga, avenida los chasquis n° 123"
        />
        <!-- Telefono Convencional -->
        <flux:input
            wire:model="phone"
            type="text"
            :label="__('Telefono')"
            required
            placeholder="0239912340"
            />
        <!-- Telefono convencional -->
        <flux:input
            wire:model="cellphone"
            type="text"
            :label="__('Celular')"
            required
            placeholder="0999999999"
        />
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />
        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
        />
        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
