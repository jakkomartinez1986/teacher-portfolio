<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|')]
    public string $email_dni = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    // public function login(): void
    // {
    //     $this->validate();

    //     $this->ensureIsNotRateLimited();

    //     // if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
    //     //     RateLimiter::hit($this->throttleKey());

    //     //     throw ValidationException::withMessages([
    //     //         'email' => __('auth.failed'),
    //     //     ]);
    //     // }
    //     $login_name=filter_var($this->email_dni, FILTER_VALIDATE_EMAIL) ? 'email' : 'dni'; 

    //         if (! Auth::attempt([ $login_name => $this->email_dni, 'password' => $this->password], $this->remember)) {
    //             RateLimiter::hit($this->throttleKey());

    //             throw ValidationException::withMessages([
    //                 'email_dni' => __('auth.failed'),
    //             ]);
    //         }


    //     RateLimiter::clear($this->throttleKey());
    //     Session::regenerate();

    //     $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    // }
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $login_name = filter_var($this->email_dni, FILTER_VALIDATE_EMAIL) ? 'email' : 'dni'; 

        if (! Auth::attempt([$login_name => $this->email_dni, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email_dni' => __('auth.failed'),
            ]);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario tiene el rol "estudiante"
        if ($user->hasRole('ESTUDIANTE')) {
            // Cerrar la sesión del usuario
            Auth::logout();
            Session::regenerate();

            throw ValidationException::withMessages([
                'email_dni' => __('auth.student_not_allowed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email_dni' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email_dni).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
        wire:model="email_dni"
         type="text"
        :label="__('Email/Dni')"
        required
        autofocus
        placeholder="Email o Dni"
        :autocomplete="filter_var($this->email_dni, FILTER_VALIDATE_EMAIL) ? 'email' : 'dni'"/>
        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute right-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Don\'t have an account?') }}
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div>
