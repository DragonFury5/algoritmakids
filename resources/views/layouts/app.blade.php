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

    <nav class="bg-white border-b border-kid-border">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-extrabold border-kid-coral">
                Algoritma<span class="border-kid-coral">Kids</span>
            </a>
            <div class="flex items-center gap-4 text-sm">
                <div class="flex gap-1 bg-kid-bg rounded-full p-1">
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="px-3 py-1 rounded-full {{ app()->getLocale() === 'id' ? 'bg-kid-teal text-white' : 'text-gray-600' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-3 py-1 rounded-full {{ app()->getLocale() === 'en' ? 'bg-kid-teal text-white' : 'text-gray-600' }}">EN</a>
                </div>
                <span class="hidden md:inline text-gray-500">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="border-kid-coral font-bold hover:underline">{{ __('ui.logout') }}</button>
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