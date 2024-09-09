<div class="min-h-screen bg-gray-900">
    <livewire:layout.navigation />

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-green-700 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <main>
        {{ $slot }}
    </main>
</div>

