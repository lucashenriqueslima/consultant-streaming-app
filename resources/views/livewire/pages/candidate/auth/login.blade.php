<?php

use App\Livewire\Candidate\Forms\LoginForm;
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
        // $this->validate();
        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('candidate.dashboard', absolute: false), navigate: true);
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
        <!-- CPF -->
        <div class="mt-4">
            <x-input-label for="cpf" value="CPF" />
            <x-text-input
                wire:model="form.cpf"
                id="cpf"
                class="block mt-1 w-full"
                name="cpf"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('form.cpf')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mt-4">

                <a class="underline text-md text-gray-400 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800" href="{{ route('candidate.register') }}" wire:navigate>
                    Ainda não é um consultor?
                </a>


            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
@script
<script>

        const cpfInput = document.getElementById('cpf');

        // Configuração da máscara para CPF
        const maskOptions = {
            mask: '000.000.000-00', // Formato do CPF
            lazy: false // Para que a máscara apareça imediatamente
        };

        IMask(cpfInput, maskOptions);

</script>
@endscript
