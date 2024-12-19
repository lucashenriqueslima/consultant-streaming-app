<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold ml-10 text-xl text-gray-100 leading-tight">
            Certificados
        </h2>
    </x-slot>

    <div class="py-12">
        @if ($certificates->isEmpty()):
            <h1 class="flex mb-6 lg:px-6 md:px-10 px-5 lg:mx-40 md:mx-24 mx-5 font-bold text-3xl text-gray-100">
                Você ainda não possui certificados
            </h1>
        @endif
        <div class="flex flex-wrap lg:mx-40 md:mx-24 mx-10">
            @foreach ($certificates as $certificate)
                <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 p-4">
                    <div class="bg-white shadow-lg rounded-lg p-6 relative overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('assets/img/certificate-example.jpg') }}');"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $certificate->name }}</h2>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-700 font-bold">{{ $certificate->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="mt-10 flex justify-end">
                                <a href="{{ route('candidate.certificates.download', $certificate->id) }}" class="inline-block bg-blue-600 text-white text-lg py-2 px-6 rounded-md hover:bg-blue-500 transition">Baixar</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
