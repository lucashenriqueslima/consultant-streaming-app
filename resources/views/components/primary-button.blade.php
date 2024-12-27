<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'relative inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'
]) }}>
    <!-- Spinner (Loading Animation) -->
    <svg wire:loading wire:target="{{ $attributes->get('wire:target') }}"
        class="absolute h-5 w-5 text-white animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
    <!-- Button Text -->
    <span
        wire:loading.class="opacity-0"
        wire:loading.remove.class="opacity-100"
        wire:target="{{ $attributes->get('wire:target') }}"
        class="transition-opacity duration-150">
        {{ $slot }}
    </span>
</button>
