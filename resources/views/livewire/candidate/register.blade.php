<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-green-900">
    <div class="wrapper w-full md:max-w-5xl mx-auto py-10 px-4 my-5 bg-white rounded-md shadow-md ">
        <form wire:submit="submit">
            {{ $this->form }}
            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3">
                    Cadastrar
                </x-primary-button>
            </div>
        </form>

    </div>
</div>
