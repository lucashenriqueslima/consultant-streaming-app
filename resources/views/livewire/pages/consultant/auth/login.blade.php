<?php

use App\Livewire\Consultant\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Enums\Association;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public array $associations;

    public function mount(): void
    {
        $this->associations = Association::getSelectOptions();
    }

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div>
            <x-input-label for="association" value="Associação" />
            <x-select-input
                wire:model="form.association"
                id="association"
                name="association"
                :options="$associations"
                selected="{{ $form->association }}"
                required
            />
            <x-input-error :messages="$errors->get('form.association')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="firstFourCpfNumbers" value="Primeiros 4 números do CPF" />

            <x-text-input wire:model="form.firstFourCpfNumbers" id="firstFourCpfNumbers" class="block mt-1 w-full"
                            type="password"
                            name="firstFourCpfNumbers"
                            maxlength="4"
                            minlength="4"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.firstFourCpfNumbers')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded bg-gray-900 border-gray-700 text-indigo-600 shadow-sm focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-400 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800" href="{{ route('password.request') }}" wire:navigate>
                    Esqueceu seu Email?
                </a>
            @else
                <a class="underline text-md text-gray-400 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800" href="{{ route('candidate.register') }}" {{-- wire:navigate --}}>
                    Ainda não é um consultor?
                </a>
            @endif
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
