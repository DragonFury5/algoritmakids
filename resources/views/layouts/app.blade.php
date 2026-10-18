<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'AlgoritmaKids') }}</title>
    <script>
    window.soundEnabled = {{ auth()->user()->sound_enabled ? 'true' : 'false' }};
</script>

    <script>
        window.soundEnabled = {{ auth()->user()->sound_enabled ? 'true' : 'false' }};
        
              // Heartbeat Tracker
        @auth
        document.addEventListener('DOMContentLoaded', () => {
            setInterval(() => {
                // Only ping if the tab is currently visible/active
                if (document.visibilityState === 'visible') {
                    fetch('{{ route('heartbeat') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ seconds: 10 }) // Send 10 seconds
                    }).catch(() => {});
                }
            }, 10000); // 10,000 ms = 10 seconds
        });
        @endauth
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased min-h-screen">

   <nav class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 md:px-6 py-3 flex items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="text-xl md:text-2xl font-extrabold shrink-0">
            <span class="text-kid-blue">Algoritma</span><span class="text-kid-mint">Kids</span>
        </a>

        <div class="flex items-center gap-3 md:gap-4 min-w-0">
            <span class="hidden md:inline text-gray-500 truncate max-w-[140px]">
                {{ auth()->user()->name }}
            </span>

            {{-- Gear → Settings --}}
            <a href="{{ route('settings') }}"
               class="group p-2 rounded-full hover:bg-kid-bg transition"
               title="{{ app()->getLocale() === 'id' ? 'Pengaturan' : 'Settings' }}">
                <x-lucide-settings
                    class="w-5 h-5 text-kid-text transition-transform duration-500 ease-out group-hover:rotate-180"
                    stroke-width="2.5" />
            </a>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button class="text-kid-coral font-bold text-sm md:text-base hover:underline whitespace-nowrap px-2 py-1">
                    {{ __('ui.logout') }}
                </button>
            </form>
        </div>
    </div>
</nav>

    <main class="max-w-6xl mx-auto px-6 py-10">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>