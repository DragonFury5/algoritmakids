<div>
    {{-- ==================== HEADER ==================== --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold">
                {{ app()->getLocale() === 'id' ? 'Halo' : 'Hello' }}, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-500 mt-1 text-sm md:text-base">
                {{ app()->getLocale() === 'id'
                    ? 'Kelola akun anak Anda di sini.'
                    : 'Manage your children\'s accounts here.' }}
            </p>
        </div>

        <livewire:parent.add-child />
    </div>

    {{-- ==================== SECTION TITLE ==================== --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-extrabold">
            {{ app()->getLocale() === 'id' ? 'Anak-Anak Anda' : 'Your Children' }}
        </h2>
        @if(! $children->isEmpty())
            <span class="text-xs text-gray-400 font-semibold">
                {{ $children->count() }} {{ app()->getLocale() === 'id' ? 'anak' : 'children' }}
            </span>
        @endif
    </div>

    {{-- ==================== EMPTY STATE ==================== --}}
    @if($children->isEmpty())
        <div class="bg-white rounded-kid border border-gray-200 p-10 text-center">
            <x-lucide-user-plus class="w-14 h-14 mx-auto mb-4 text-kid-blue animate-pulse-soft" stroke-width="1.5" />
            <p class="text-gray-500">
                {{ app()->getLocale() === 'id'
                    ? 'Belum ada akun anak. Klik "Tambah Anak" untuk membuat.'
                    : 'No child accounts yet. Click "Add Child" to create one.' }}
            </p>
        </div>
    @else
        {{-- ==================== CHILDREN PATH ==================== --}}
        <div class="space-y-3">
            @foreach($children as $child)
                @php
                    $avatarColors = [
                        'robot-blue'   => '#7EC8E3',
                        'robot-green'  => '#A8E6CF',
                        'robot-yellow' => '#FFD97D',
                        'robot-coral'  => '#FF8B94',
                        'robot-purple' => '#B39DDB',
                        'robot-mint'   => '#B8E0D2',
                    ];
                    $avatarBg = $avatarColors[$child->avatar] ?? '#7EC8E3';
                    $tierCfg = $child->tierConfig();
                    $stats = $child->stats;

                    $colorMap = [
                        'mint'   => 'bg-kid-mint',
                        'yellow' => 'bg-kid-yellow',
                        'coral'  => 'bg-kid-coral',
                        'purple' => 'bg-kid-purple',
                    ];
                    $tierBg = $colorMap[$tierCfg['color']] ?? 'bg-kid-mint';
                @endphp

               <a href="{{ route('parent.child.show', $child) }}"
   class="block bg-white rounded-kid border border-gray-200 p-4 md:p-5 card-lift hover:border-kid-blue transition">
                    <div class="flex items-center gap-3 md:gap-4">

                        {{-- Avatar --}}
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: {{ $avatarBg }}">
                            <x-lucide-bot class="w-7 h-7 md:w-8 md:h-8 text-white" stroke-width="2" />
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-extrabold text-base md:text-lg truncate">{{ $child->name }}</h3>
                                <span class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-full text-white {{ $tierBg }}">
                                    <x-dynamic-component :component="'lucide-' . $tierCfg['icon']" class="w-3 h-3" stroke-width="3" />
                                    {{ $tierCfg['label_' . app()->getLocale()] }}
                                </span>
                            </div>
                            <p class="text-xs md:text-sm text-gray-500 truncate">{{ $child->email }}</p>

                            {{-- Progress bar --}}
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full bg-kid-blue transition-all duration-500"
                                         style="width: {{ $stats['percent'] }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-500 shrink-0 tabular-nums">
                                    {{ $stats['levels_completed'] }}/{{ $stats['levels_available'] }}
                                    <x-lucide-flag class="inline w-3 h-3 -mt-0.5 text-kid-coral" stroke-width="2.5" />
                                </span>
                            </div>
                        </div>

                                           {{-- Stats --}}
                    <div class="hidden md:flex items-center gap-6 shrink-0">
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1 text-lg font-extrabold">
                                {{ $stats['stars'] }}
                                <x-lucide-star class="w-4 h-4 text-kid-yellow fill-current" stroke-width="1.5" />
                            </div>
                            <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">
                                {{ app()->getLocale() === 'id' ? 'Bintang' : 'Stars' }}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1 text-lg font-extrabold">
                                {{ $child->formatted_time_spent }}
                            </div>
                            <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold flex items-center gap-1">
                                <x-lucide-clock class="w-3 h-3" stroke-width="2.5" />
                                {{ app()->getLocale() === 'id' ? 'Waktu' : 'Time' }}
                            </div>
                        </div>
                    </div>

                    {{-- Mobile stats --}}
                    <div class="md:hidden mt-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1 font-bold text-gray-600">
                            {{ $stats['stars'] }}
                            <x-lucide-star class="w-3 h-3 text-kid-yellow fill-current" stroke-width="1.5" />
                            <span class="text-gray-400 font-normal">
                                {{ app()->getLocale() === 'id' ? 'bintang' : 'stars' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>