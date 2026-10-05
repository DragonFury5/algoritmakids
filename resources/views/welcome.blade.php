<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'AlgoritmaKids') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kid-bg font-kid text-kid-text antialiased">

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-2xl font-extrabold text-kid-blue">
            Algoritma<span class="text-kid-coral">Kids</span>
        </a>

        <div class="flex items-center gap-4 text-sm">
            {{-- Language switcher --}}
            <div class="flex gap-1 bg-white rounded-full shadow-sm p-1">
                <a href="{{ route('lang.switch', 'id') }}"
                   class="px-3 py-1 rounded-full {{ app()->getLocale() === 'id' ? 'bg-kid-blue text-white' : 'text-gray-600' }}">
                    ID
                </a>
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-3 py-1 rounded-full {{ app()->getLocale() === 'en' ? 'bg-kid-blue text-white' : 'text-gray-600' }}">
                    EN
                </a>
            </div>

            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isParent() ? route('parent.dashboard') : route('student.dashboard')) }}"
                   class="font-bold text-kid-blue hover:underline">
                    {{ __('ui.dashboard') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="font-bold text-kid-blue hover:underline">
                    {{ __('ui.login') }}
                </a>
                <a href="{{ route('register') }}" class="btn-kid bg-kid-blue">
                    {{ __('ui.register') }}
                </a>
            @endauth
        </div>
    </nav>

    {{-- ==================== HERO ==================== --}}
    <section class="max-w-6xl mx-auto px-6 py-12 md:py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">
                {{ __('ui.hero_title') }}
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                {{ __('ui.hero_subtitle') }}
            </p>

            <div class="flex flex-wrap gap-4">
                @guest
                    <a href="{{ route('register') }}" class="btn-kid bg-kid-coral text-lg">
                        {{ __('ui.hero_cta') }}
                    </a>
                    <a href="{{ route('login') }}" class="btn-kid bg-white text-kid-blue border-2 border-kid-blue">
                        {{ __('ui.hero_cta_secondary') }}
                    </a>
                @else
                    <a href="{{ route('parent.dashboard') }}" class="btn-kid bg-kid-coral text-lg">
                        {{ __('ui.hero_cta') }}
                    </a>
                @endguest
            </div>
        </div>

        {{-- Robot placeholder (we'll swap for animated SVG soon) --}}
        <div class="flex justify-center">
            <div class="w-64 h-64 rounded-full bg-kid-mint flex items-center justify-center shadow-lg">
                <span class="text-8xl">🤖</span>
            </div>
        </div>
    </section>

    {{-- ==================== ADVANTAGES ==================== --}}
    <section class="max-w-6xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-extrabold text-center mb-10">
            {{ __('ui.advantages_title') }}
        </h2>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['icon' => '🧠', 'title' => 'advantage_1_title', 'desc' => 'advantage_1_desc', 'color' => 'kid-blue'],
                ['icon' => '🎨', 'title' => 'advantage_2_title', 'desc' => 'advantage_2_desc', 'color' => 'kid-yellow'],
                ['icon' => '🛡️', 'title' => 'advantage_3_title', 'desc' => 'advantage_3_desc', 'color' => 'kid-mint'],
            ] as $card)
                <div class="bg-white rounded-kid shadow-md p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-{{ $card['color'] }} flex items-center justify-center mx-auto mb-4 text-3xl">
                        {{ $card['icon'] }}
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __("ui.{$card['title']}") }}</h3>
                    <p class="text-gray-600">{{ __("ui.{$card['desc']}") }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ==================== CURRICULUM ==================== --}}
    <section class="max-w-6xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-extrabold text-center mb-10">
            {{ __('ui.curriculum_title') }}
        </h2>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['num' => '1', 'title' => 'curriculum_1_title', 'desc' => 'curriculum_1_desc', 'color' => 'kid-blue'],
                ['num' => '2', 'title' => 'curriculum_2_title', 'desc' => 'curriculum_2_desc', 'color' => 'kid-purple'],
                ['num' => '3', 'title' => 'curriculum_3_title', 'desc' => 'curriculum_3_desc', 'color' => 'kid-coral'],
            ] as $mod)
                <div class="bg-white rounded-kid shadow-md p-6">
                    <div class="w-12 h-12 rounded-full bg-{{ $mod['color'] }} text-white flex items-center justify-center font-extrabold text-xl mb-4">
                        {{ $mod['num'] }}
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __("ui.{$mod['title']}") }}</h3>
                    <p class="text-gray-600">{{ __("ui.{$mod['desc']}") }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ==================== HOW TO PLAY ==================== --}}
    <section class="max-w-4xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-extrabold text-center mb-10">
            {{ __('ui.how_title') }}
        </h2>

        <ol class="space-y-4">
            @foreach(['how_step_1', 'how_step_2', 'how_step_3'] as $i => $step)
                <li class="bg-white rounded-kid shadow-sm p-5 flex items-center gap-4">
                    <span class="w-10 h-10 rounded-full bg-kid-blue text-white flex items-center justify-center font-extrabold">
                        {{ $i + 1 }}
                    </span>
                    <span class="text-lg">{{ __("ui.$step") }}</span>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="border-t border-gray-200 mt-20">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>© {{ date('Y') }} AlgoritmaKids. {{ __('ui.made_with_love') }}</p>
            <div class="flex gap-6">
                <a href="{{ route('privacy') }}" class="hover:text-kid-blue">
                    {{ __('ui.privacy') }}
                </a>
            </div>
        </div>
    </footer>

</body>
</html>