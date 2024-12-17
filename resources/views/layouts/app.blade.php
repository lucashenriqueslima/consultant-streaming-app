<div class="min-h-screen bg-gray-900">
    @if (auth('web')->check())
        <livewire:layout.consultant.navigation />
    @elseif (auth('candidate')->check())
        <livewire:layout.candidate.navigation />
    @endif

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

