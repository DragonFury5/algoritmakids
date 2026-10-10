<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'AlgoritmaKids') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased overflow-x-hidden">

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="bg-white/90 backdrop-blur border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold">
                <span class="text-kid-blue">Algoritma</span><span class="text-kid-mint">Kids</span>
            </a>

            <div class="flex items-center gap-4 text-sm">
                <div class="flex gap-1 bg-kid-bg rounded-full p-1">
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="px-3 py-1 rounded-full {{ app()->getLocale() === 'id' ? 'bg-kid-blue text-white' : 'text-gray-600' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-3 py-1 rounded-full {{ app()->getLocale() === 'en' ? 'bg-kid-blue text-white' : 'text-gray-600' }}">EN</a>
                </div>

                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isParent() ? route('parent.dashboard') : route('student.dashboard')) }}"
                       class="font-bold text-kid-blue hover:underline">
                        {{ __('ui.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-kid-text hover:text-kid-blue">
                        {{ __('ui.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn-kid bg-kid-blue text-white text-sm">
                        {{ __('ui.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ==================== HERO ==================== --}}
    <section class="relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-kid-yellow/30 blur-3xl animate-pulse-soft"></div>
        <div class="absolute top-40 -left-32 w-80 h-80 rounded-full bg-kid-mint/30 blur-3xl animate-pulse-soft" style="animation-delay: -1.5s;"></div>

        <div class="relative max-w-6xl mx-auto px-6 pt-16 pb-24 md:pt-24 md:pb-32">
            <div class="grid md:grid-cols-12 gap-8 items-center">

                <div class="md:col-span-7 relative z-10">
                    <span class="inline-block bg-kid-blue/10 text-kid-blue font-bold text-xs uppercase tracking-wider px-4 py-1.5 rounded-full mb-6">
                        {{ app()->getLocale() === 'id' ? 'Untuk anak usia 8–12 tahun' : 'For kids aged 8–12' }}
                    </span>

                    <h1 class="text-4xl md:text-6xl font-extrabold leading-[1.1] mb-6">
                        {{ __('ui.hero_title') }}
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-lg">
                        {{ __('ui.hero_subtitle') }}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn-kid bg-kid-blue text-white text-base md:text-lg px-8 py-4">
                                <span>{{ __('ui.hero_cta') }}</span>
                                <x-lucide-arrow-right class="w-5 h-5 ml-2" stroke-width="2.5" />
                            </a>
                            <a href="{{ route('login') }}" class="btn-kid bg-white text-kid-text border-2 border-gray-200 text-base md:text-lg px-6 py-4">
                                {{ __('ui.hero_cta_secondary') }}
                            </a>
                        @else
                            @php
                                $dashRoute = auth()->user()->isAdmin()
                                    ? route('admin.dashboard')
                                    : (auth()->user()->isParent()
                                        ? route('parent.dashboard')
                                        : route('student.dashboard'));
                            @endphp
                            <a href="{{ $dashRoute }}" class="btn-kid bg-kid-blue text-white text-base md:text-lg px-8 py-4">
                                <span>{{ __('ui.hero_cta') }}</span>
                                <x-lucide-arrow-right class="w-5 h-5 ml-2" stroke-width="2.5" />
                            </a>
                        @endguest
                    </div>
                </div>

                <div class="md:col-span-5 relative">
                    <div class="relative flex justify-center">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-72 h-72 md:w-80 md:h-80 rounded-full bg-gradient-to-br from-kid-mint/40 to-kid-blue/20"></div>
                        </div>

                       <div class="absolute top-4 left-4 text-kid-yellow animate-float" style="animation-delay: -1s;">
    <x-lucide-star class="w-10 h-10 fill-current" stroke-width="1.5" />
</div>
<div class="absolute bottom-8 right-2 text-kid-purple animate-float" style="animation-delay: -2s;">
    <x-lucide-sparkles class="w-8 h-8" stroke-width="2" />
</div>
<div class="absolute top-20 right-4 text-kid-mint animate-spin-slow">
    <x-lucide-code class="w-9 h-9" stroke-width="2.5" />
</div>
<div class="absolute bottom-4 left-2 text-kid-blue animate-pulse-soft">
    <x-lucide-zap class="w-8 h-8 fill-current" stroke-width="1.5" />
</div>

                     <svg viewBox="0 0 200 200" class="w-64 h-64 md:w-72 md:h-72 relative z-10 drop-shadow-2xl animate-float">
                            <line x1="100" y1="45" x2="100" y2="20" stroke="#2D2A26" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="100" cy="18" r="8" fill="#FF6B6B"/>
                            <rect x="30" y="95" width="12" height="30" rx="6" fill="#4ECDC4"/>
                            <rect x="158" y="95" width="12" height="30" rx="6" fill="#4ECDC4"/>
                            <rect x="45" y="50" width="110" height="110" rx="28" fill="#FF6B6B" stroke="#2D2A26" stroke-width="3"/>
                            <rect x="60" y="70" width="80" height="55" rx="20" fill="#FFF8F0"/>
                            <circle cx="80" cy="95" r="8" fill="#2D2A26"/>
                            <circle cx="120" cy="95" r="8" fill="#2D2A26"/>
                            <circle cx="83" cy="92" r="3" fill="white"/>
                            <circle cx="123" cy="92" r="3" fill="white"/>
                            <path d="M 85 112 Q 100 122 115 112" stroke="#2D2A26" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <rect x="80" y="135" width="40" height="15" rx="6" fill="#2D2A26"/>
                            <circle cx="90" cy="142" r="2.5" fill="#4ECDC4"/>
                            <circle cx="100" cy="142" r="2.5" fill="#FCD34D"/>
                            <circle cx="110" cy="142" r="2.5" fill="#FF6B6B"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== ADVANTAGES (Bento) ==================== --}}
    <section class="max-w-6xl mx-auto px-6 py-16">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-12">
            {{ __('ui.advantages_title') }}
        </h2>

        <div class="grid md:grid-cols-5 gap-5">
            <div class="md:col-span-3 bg-kid-blue rounded-kid p-8 md:p-10 text-white relative overflow-hidden">
                <div class="absolute -bottom-8 -right-8 opacity-20">
                    <x-lucide-brain class="w-56 h-56" stroke-width="1" />
                </div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mb-6">
                        <x-lucide-brain class="w-7 h-7" stroke-width="2" />
                    </div>
                    <h3 class="text-2xl md:text-3xl font-extrabold mb-3">
                        {{ __('ui.advantage_1_title') }}
                    </h3>
                    <p class="text-white/90 text-lg max-w-md">
                        {{ __('ui.advantage_1_desc') }}
                    </p>
                </div>
            </div>

            <div class="md:col-span-2 flex flex-col gap-5"><div class="bg-white rounded-kid p-6 border-2 border-kid-yellow/40 flex items-start gap-4 card-lift">
                    <div class="w-12 h-12 rounded-xl bg-kid-yellow flex items-center justify-center shrink-0">
                        <x-lucide-palette class="w-6 h-6 text-kid-text" stroke-width="2" />
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold mb-1">{{ __('ui.advantage_2_title') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('ui.advantage_2_desc') }}</p>
                    </div>
                </div>

              <div class="bg-white rounded-kid p-6 border-2 border-kid-mint/40 flex items-start gap-4 card-lift">
                    <div class="w-12 h-12 rounded-xl bg-kid-mint flex items-center justify-center shrink-0">
                        <x-lucide-shield-check class="w-6 h-6 text-white" stroke-width="2" />
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold mb-1">{{ __('ui.advantage_3_title') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('ui.advantage_3_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== CURRICULUM (Zigzag) ==================== --}}
    <section class="max-w-4xl mx-auto px-6 py-16">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-12 text-center">
            {{ __('ui.curriculum_title') }}
        </h2>

        <div class="relative">
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 border-l-2 border-dashed border-gray-300 -translate-x-px"></div>

            @php
                $steps = [
                    ['num' => 1, 'title' => 'curriculum_1_title', 'desc' => 'curriculum_1_desc', 'color' => 'bg-kid-blue',   'icon' => 'list-ordered'],
                    ['num' => 2, 'title' => 'curriculum_2_title', 'desc' => 'curriculum_2_desc', 'color' => 'bg-kid-purple', 'icon' => 'repeat'],
                    ['num' => 3, 'title' => 'curriculum_3_title', 'desc' => 'curriculum_3_desc', 'color' => 'bg-kid-mint',   'icon' => 'git-branch'],
                ];
            @endphp

            @foreach($steps as $i => $step)
                <div class="relative flex items-start mb-10 last:mb-0 {{ $i % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                    <div class="absolute left-8 md:left-1/2 -translate-x-1/2 z-10">
                        <div class="w-16 h-16 rounded-full {{ $step['color'] }} border-4 border-kid-bg flex items-center justify-center text-white font-extrabold text-xl shadow-md">
                            {{ $step['num'] }}
                        </div>
                    </div>

                    <div class="w-full md:w-1/2 {{ $i % 2 === 0 ? 'md:pr-16 md:text-right' : 'md:pl-16' }} pl-24 md:pl-0">
                       <div class="bg-white rounded-kid p-6 shadow-sm border border-gray-100 card-lift">
                            <div class="flex items-center gap-3 mb-3 {{ $i % 2 === 0 ? 'md:justify-end' : '' }}">
                                <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-5 h-5 text-gray-500" stroke-width="2.5" />
                                <h3 class="text-xl font-extrabold">{{ __("ui.{$step['title']}") }}</h3>
                            </div>
                            <p class="text-gray-600">{{ __("ui.{$step['desc']}") }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ==================== HOW TO PLAY ==================== --}}
    <section class="max-w-6xl mx-auto px-6 py-16">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-12 text-center">
            {{ __('ui.how_title') }}
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            @php
                $howSteps = [
                    ['num' => 1, 'key' => 'how_step_1', 'icon' => 'user-plus', 'bg' => 'bg-kid-blue'],
                    ['num' => 2, 'key' => 'how_step_2', 'icon' => 'mouse-pointer-click', 'bg' => 'bg-kid-purple'],
                    ['num' => 3, 'key' => 'how_step_3', 'icon' => 'trophy', 'bg' => 'bg-kid-mint'],
                ];
            @endphp

            @foreach($howSteps as $step)
                <div class="text-center">
                    <div class="w-24 h-24 rounded-full {{ $step['bg'] }} flex items-center justify-center mx-auto mb-5 shadow-md">
                        <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-10 h-10 text-white" stroke-width="2" />
                    </div>
                    <h3 class="text-lg font-extrabold mb-2">
                        {{ $step['num'] }}. {{ __('ui.' . $step['key']) }}
                    </h3>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ==================== FINAL CTA ==================== --}}
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="bg-kid-text rounded-kid p-10 md:p-16 text-center relative overflow-hidden">
           <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-kid-blue/20 blur-3xl animate-pulse-soft"></div>
          <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full bg-kid-mint/20 blur-3xl animate-pulse-soft" style="animation-delay: -1.5s;"></div>

            <div class="relative z-10">
                <h2 class="text-2xl md:text-4xl font-extrabold text-white mb-4">
                    {{ app()->getLocale() === 'id' ? 'Siap memulai petualangan coding?' : 'Ready to start the coding adventure?' }}
                </h2>
                <p class="text-white/70 mb-8 max-w-lg mx-auto">
                    {{ app()->getLocale() === 'id' ? 'Gratis. Tanpa iklan. Aman untuk anak.' : 'Free. No ads. Safe for kids.' }}
                </p>
                <a href="{{ route('register') }}" class="btn-kid bg-kid-blue text-white text-lg px-8 py-4 inline-flex">
                    <span>{{ __('ui.hero_cta') }}</span>
                    <x-lucide-arrow-right class="w-5 h-5 ml-2" stroke-width="2.5" />
                </a>
            </div>
        </div>
    </section>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="border-t border-gray-200 mt-12">
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