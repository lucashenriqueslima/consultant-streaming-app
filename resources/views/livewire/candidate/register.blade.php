<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-blue-900">
    <div class="bg-[#233876] p-1.5 px-3.5 translate-y-11 rounded-[20%]">
        <x-application-logo class="w-auto h-12 mx-auto" />
    </div>
    <div class="wrapper w-full md:max-w-5xl mx-auto py-10 px-4 my-5 bg-white rounded-md shadow-md ">
        <form wire:submit.prevent="submit">
            {{ $this->form }}
            <div class="flex items-center justify-end mt-4">
                <x-primary-button wire:target="submit">
                    Cadastrar
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
