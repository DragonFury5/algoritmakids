<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'AlgoritmaKids') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased min-h-screen">

    <nav class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 md:px-6 py-3 flex items-center justify-between gap-2">
        <a href="{{ route('home') }}" class="text-lg md:text-xl font-extrabold shrink-0">
            <span class="text-kid-blue">Algoritma</span><span class="text-kid-mint">Kids</span>
        </a>

        <div class="flex items-center gap-2 md:gap-4 text-sm min-w-0">
            <div class="flex gap-0.5 bg-kid-bg rounded-full p-1 shrink-0">
                <a href="{{ route('lang.switch', 'id') }}"
                   class="px-2 md:px-3 py-1 rounded-full text-xs md:text-sm
                          {{ app()->getLocale() === 'id' ? 'bg-kid-blue text-white' : 'text-gray-600' }}">
                    ID
                </a>
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-2 md:px-3 py-1 rounded-full text-xs md:text-sm
                          {{ app()->getLocale() === 'en' ? 'bg-kid-blue text-white' : 'text-gray-600' }}">
                    EN
                </a>
            </div>

            <span class="hidden md:inline text-gray-500 truncate max-w-[120px]">
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button class="text-kid-coral font-bold text-xs md:text-sm hover:underline whitespace-nowrap">
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