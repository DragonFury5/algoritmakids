<div>
    {{-- ==================== HEADER ==================== --}}
    <div class="animate-pop-in bg-white rounded-kid border border-gray-200 p-5 md:p-6 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">

            <div class="flex items-center gap-4 w-full md:w-auto">
                @php
                    $avatarColors = [
                        'robot-blue'   => '#7EC8E3',
                        'robot-green'  => '#A8E6CF',
                        'robot-yellow' => '#FFD97D',
                        'robot-coral'  => '#FF8B94',
                        'robot-purple' => '#B39DDB',
                        'robot-mint'   => '#B8E0D2',
                    ];
                    $avatarBg = $avatarColors[auth()->user()->avatar] ?? '#7EC8E3';
                    $tierCfg = config('tiers.' . $tier);
                @endphp

                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center shrink-0"
                     style="background-color: {{ $avatarBg }}">
                    <x-lucide-bot class="w-7 h-7 md:w-8 md:h-8 text-white" stroke-width="2" />
                </div>

                <div class="min-w-0 flex-1">
                    <h1 class="text-xl md:text-2xl font-extrabold truncate">
                        {{ app()->getLocale() === 'id' ? 'Hai' : 'Hi' }}, {{ auth()->user()->name }}!
                    </h1>
                    <div class="flex items-center gap-2 mt-0.5 text-xs">
                        <span class="inline-flex items-center gap-1 font-bold text-kid-blue">
                            <x-dynamic-component :component="'lucide-' . $tierCfg['icon']" class="w-3 h-3" stroke-width="2.5" />
                            {{ $tierCfg['label_' . app()->getLocale()] }}
                        </span>
                        <span class="text-gray-300">·</span>
                        <span class="text-gray-500">
                            {{ app()->getLocale() === 'id' ? 'Ayo belajar!' : 'Let\'s learn!' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Stat chips --}}
            <div class="flex gap-2 w-full md:w-auto">
                <div class="flex-1 md:flex-none flex items-center gap-2 bg-kid-bg rounded-kid px-3 py-2">
                    <x-lucide-star class="w-4 h-4 text-kid-yellow fill-current shrink-0" stroke-width="1.5" />
                    <div class="leading-tight">
                        <div class="text-base md:text-lg font-extrabold">{{ $totalStars }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                            {{ app()->getLocale() === 'id' ? 'Bintang' : 'Stars' }}
                        </div>
                    </div>
                </div>
                <div class="flex-1 md:flex-none flex items-center gap-2 bg-kid-bg rounded-kid px-3 py-2">
                    <x-lucide-award class="w-4 h-4 text-kid-coral shrink-0" stroke-width="2.5" />
                    <div class="leading-tight">
                        <div class="text-base md:text-lg font-extrabold">{{ $badgeCount }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                            {{ app()->getLocale() === 'id' ? 'Lencana' : 'Badges' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODULE PATH ==================== --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-extrabold">
            {{ app()->getLocale() === 'id' ? 'Jalur Belajar' : 'Learning Path' }}
        </h2>
        @if(! $modules->isEmpty())
            <span class="text-xs text-gray-400 font-semibold">
                {{ $modules->count() }} {{ app()->getLocale() === 'id' ? 'modul' : 'modules' }}
            </span>
        @endif
    </div>

    @if($modules->isEmpty())
        <div class="bg-white rounded-kid border border-gray-200 p-10 text-center">
            <x-lucide-package-open class="w-12 h-12 mx-auto mb-3 text-gray-300" stroke-width="1.5" />
            <p class="text-gray-500">
                {{ app()->getLocale() === 'id' ? 'Belum ada modul untuk levelmu. Hubungi admin.' : 'No modules for your tier yet. Contact admin.' }}
            </p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($modules as $i => $module)
                @php
                    $colorBg   = 'bg-' . $module->style_color;
                    $colorText = 'text-' . $module->style_color;
                    $firstLevel = $module->levels->first();
                    $prog = $module->progress;
                @endphp

                <div class="bg-white rounded-kid border border-gray-200 p-4 md:p-5 card-lift">
                    <div class="flex items-center gap-3 md:gap-4">

                        {{-- Module icon --}}
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-kid {{ $colorBg }} flex items-center justify-center shrink-0">
                            <x-dynamic-component :component="'lucide-' . $module->style_icon"
                                                 class="w-6 h-6 md:w-7 md:h-7 text-white" stroke-width="2.5" />
                        </div>

                        {{-- Info + progress --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] uppercase tracking-wider font-extrabold text-gray-400">
                                    {{ app()->getLocale() === 'id' ? 'Modul' : 'Module' }} {{ $i + 1 }}
                                </span>
                                @if($prog['finished'])
                                    <span class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-extrabold text-kid-mint">
                                        <x-lucide-check-circle class="w-3 h-3" stroke-width="3" />
                                        {{ app()->getLocale() === 'id' ? 'Selesai' : 'Done' }}
                                    </span>
                                @elseif($prog['started'])
                                    <span class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-extrabold text-kid-yellow">
                                        <x-lucide-circle-dot class="w-3 h-3" stroke-width="3" />
                                        {{ app()->getLocale() === 'id' ? 'Berjalan' : 'In Progress' }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-base md:text-lg font-extrabold truncate">{{ $module->title }}</h3>
                            <p class="text-xs md:text-sm text-gray-500 truncate">{{ $module->description }}</p>

                            {{-- Progress bar --}}
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full {{ $colorBg }} transition-all duration-500"
                                         style="width: {{ $prog['percent'] }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-500 shrink-0 tabular-nums">
                                    {{ $prog['earned'] }}/{{ $prog['total'] }}
                                    <x-lucide-star class="inline w-3 h-3 -mt-0.5 text-kid-yellow fill-current" stroke-width="1.5" />
                                </span>
                            </div>
                        </div>

                        {{-- Action --}}
                        @if($firstLevel)
                            @if($prog['finished'])
                                <a href="{{ $firstLevel->playRoute() }}"
                                   class="shrink-0 hidden md:flex items-center gap-2 rounded-kid px-4 py-2 text-sm font-bold bg-white border-2 border-gray-200 text-kid-text hover:border-gray-300">
                                    <x-lucide-rotate-ccw class="w-4 h-4" stroke-width="2.5" />
                                    <span>{{ app()->getLocale() === 'id' ? 'Ulangi' : 'Review' }}</span>
                                </a>
                            @elseif($prog['started'])
                                <a href="{{ $firstLevel->playRoute() }}"
                                   class="shrink-0 hidden md:flex items-center gap-2 rounded-kid px-4 py-2 text-sm font-bold bg-kid-blue text-white">
                                    <x-lucide-play class="w-4 h-4 fill-current" stroke-width="2" />
                                    <span>{{ app()->getLocale() === 'id' ? 'Lanjut' : 'Continue' }}</span>
                                </a>
                            @else
                                <a href="{{ $firstLevel->playRoute() }}"
                                   class="shrink-0 hidden md:flex items-center gap-2 rounded-kid px-4 py-2 text-sm font-bold bg-kid-blue text-white">
                                    <span>{{ app()->getLocale() === 'id' ? 'Mulai' : 'Start' }}</span>
                                    <x-lucide-arrow-right class="w-4 h-4" stroke-width="2.5" />
                                </a>
                            @endif
                        @endif
                    </div>

                    {{-- Mobile action --}}
                    @if($firstLevel)
                        <a href="{{ $firstLevel->playRoute() }}"
                           class="md:hidden mt-3 flex items-center justify-center gap-2 rounded-kid px-4 py-2.5 text-sm font-bold
                                  {{ $prog['finished']
                                     ? 'bg-white border-2 border-gray-200 text-kid-text'
                                     : 'bg-kid-blue text-white' }}">
                            @if($prog['finished'])
                                <x-lucide-rotate-ccw class="w-4 h-4" stroke-width="2.5" />
                                <span>{{ app()->getLocale() === 'id' ? 'Ulangi' : 'Review' }}</span>
                            @elseif($prog['started'])
                                <x-lucide-play class="w-4 h-4 fill-current" stroke-width="2" />
                                <span>{{ app()->getLocale() === 'id' ? 'Lanjut' : 'Continue' }}</span>
                            @else
                                <span>{{ app()->getLocale() === 'id' ? 'Mulai' : 'Start' }}</span>
                                <x-lucide-arrow-right class="w-4 h-4" stroke-width="2.5" />
                            @endif
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>