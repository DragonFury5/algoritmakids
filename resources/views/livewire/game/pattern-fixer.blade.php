<div class="max-w-5xl mx-auto"
     x-data="patternFixer({
        width: {{ $level->game_data['width'] }},
        height: {{ $level->game_data['height'] }},
        start: {{ Js::from($level->game_data['start']) }},
        goal: {{ Js::from($level->game_data['goal']) }},
        walls: {{ Js::from($level->game_data['walls']) }},
        program: {{ Js::from($level->game_data['program']) }},
        bug_index: {{ $level->game_data['bug_index'] }},
        options: {{ Js::from($level->game_data['options']) }},
        level_id: {{ $level->id }}
     })">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
            <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
            <span>{{ __('ui.dashboard') }}</span>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold mt-2">{{ $level->title }}</h1>
        <p class="text-gray-600 mt-1 max-w-2xl">{{ $level->instructions }}</p>
    </div>

    <div class="grid md:grid-cols-[auto_1fr] gap-6 items-start">

        {{-- Preview Grid --}}
        <div class="bg-white rounded-kid border border-gray-200 p-5">
            <div class="relative mx-auto"
                 :style="`width: ${width * cell}px; height: ${height * cell}px;
                          background-color: #FFF8F0;
                          background-image:
                            linear-gradient(to right, #E5D9C9 1px, transparent 1px),
                            linear-gradient(to bottom, #E5D9C9 1px, transparent 1px);
                          background-size: ${cell}px ${cell}px;`">

                <template x-for="wall in walls" :key="wall">
                    <div class="absolute"
                         :style="`width: ${cell}px; height: ${cell}px;
                                  left: ${wall.split(',')[0] * cell}px;
                                  top: ${wall.split(',')[1] * cell}px;
                                  background-color: #2D2A26;`"></div>
                </template>

                <div class="absolute flex items-center justify-center"
                     :style="`width: ${cell}px; height: ${cell}px;
                              left: ${goal.x * cell}px; top: ${goal.y * cell}px;`">
                    <x-lucide-flag class="w-9 h-9 text-kid-blue" stroke-width="2.5" />
                </div>

                <div class="absolute"
                     :style="`width: ${cell}px; height: ${cell}px; ${robotStyle}`"
                     style="transition: transform 300ms ease-out;">
                    <div class="absolute inset-0 pointer-events-none"
                         :style="robotRotation"
                         style="transition: transform 200ms ease-out;">
                        <svg viewBox="0 0 64 64" class="w-full h-full">
                            <polygon points="62,32 44,18 44,46"
                                     fill="#FCD34D"
                                     stroke="#E85555"
                                     stroke-width="2"
                                     stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg viewBox="0 0 64 64" class="w-full h-full relative">
                        <line x1="32" y1="18" x2="32" y2="6" stroke="#2D2A26" stroke-width="2"/>
                        <circle cx="32" cy="6" r="3" fill="#FF6B6B"/>
                        <rect x="14" y="18" width="36" height="32" rx="10"
                              fill="#4ECDC4" stroke="#2D2A26" stroke-width="1.5"/>
                        <circle cx="25" cy="32" r="4" fill="white"/>
                        <circle cx="39" cy="32" r="4" fill="white"/>
                        <circle cx="25" cy="32" r="2" fill="#2D2A26"/>
                        <circle cx="39" cy="32" r="2" fill="#2D2A26"/>
                        <path d="M 26 42 Q 32 46 38 42"
                              stroke="#2D2A26" stroke-width="2" fill="none"
                              stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            @if($previousStars > 0)
                <div class="mt-4 text-sm text-gray-500 text-center flex items-center justify-center gap-2">
                    <span>{{ app()->getLocale() === 'id' ? 'Bintang terbaik:' : 'Best stars:' }}</span>
                    <span class="inline-flex gap-0.5">
                        @for($i = 0; $i < $previousStars; $i++)
                            <x-lucide-star class="w-4 h-4 text-kid-yellow fill-current" stroke-width="1.5" />
                        @endfor
                    </span>
                </div>
            @endif
        </div>

        {{-- Program to fix --}}
        <div class="space-y-4">

            <div class="bg-white rounded-kid border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-extrabold text-lg">
                        {{ app()->getLocale() === 'id' ? 'Perbaiki Program' : 'Fix the Program' }}
                    </h2>
                    <span class="text-xs text-gray-400 inline-flex items-center gap-1">
                        <x-lucide-mouse-pointer-click class="w-3 h-3" stroke-width="2.5" />
                        {{ app()->getLocale() === 'id' ? 'Klik blok yang salah' : 'Click the wrong block' }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-2 items-start">
                    <template x-for="(block, i) in program" :key="i">
                        <div class="relative">
                            <button
                                @click="openEditor(i)"
                                :disabled="running || won"
                                class="flex items-center justify-center rounded-kid px-4 py-3 font-extrabold text-white shadow-sm transition"
                                :class="{
                                    'bg-kid-blue': block.type === 'forward',
                                    'bg-kid-purple': block.type === 'left',
                                    'bg-kid-coral': block.type === 'right',
                                    'ring-4 ring-kid-yellow': editingIndex === i,
                                    'opacity-50': running && editingIndex !== i,
                                }">
                                <template x-if="block.type === 'forward'">
                                    <x-lucide-arrow-right class="w-6 h-6" stroke-width="2.5" />
                                </template>
                                <template x-if="block.type === 'left'">
                                    <x-lucide-rotate-ccw class="w-6 h-6" stroke-width="2.5" />
                                </template>
                                <template x-if="block.type === 'right'">
                                    <x-lucide-rotate-cw class="w-6 h-6" stroke-width="2.5" />
                                </template>
                            </button>

                            {{-- Options popover --}}
                            <template x-if="editingIndex === i">
                                <div class="absolute top-full mt-2 left-0 z-20 bg-white rounded-kid border border-gray-200 shadow-lg p-2 flex gap-2">
                                    <template x-for="opt in options" :key="opt">
                                        <button @click="chooseReplacement(i, opt)"
                                                :disabled="running"
                                                class="flex items-center justify-center rounded-kid w-11 h-11 text-white font-extrabold transition hover:scale-105"
                                                :class="{
                                                    'bg-kid-blue': opt === 'forward',
                                                    'bg-kid-purple': opt === 'left',
                                                    'bg-kid-coral': opt === 'right',
                                                }">
                                            <template x-if="opt === 'forward'">
                                                <x-lucide-arrow-right class="w-5 h-5" stroke-width="2.5" />
                                            </template>
                                            <template x-if="opt === 'left'">
                                                <x-lucide-rotate-ccw class="w-5 h-5" stroke-width="2.5" />
                                            </template>
                                            <template x-if="opt === 'right'">
                                                <x-lucide-rotate-cw class="w-5 h-5" stroke-width="2.5" />
                                            </template>
                                        </button>
                                    </template>
                                    <button @click="cancelEdit()"
                                            class="flex items-center justify-center rounded-kid w-11 h-11 bg-gray-100 text-gray-500 hover:bg-gray-200">
                                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Reset --}}
            <div class="flex gap-3">
                <button @click="resetProgram()" :disabled="running"
                        class="btn-kid bg-white border-2 border-gray-200 text-kid-text flex-1">
                    <x-lucide-rotate-ccw class="w-5 h-5 mr-2" stroke-width="2.5" />
                    <span>{{ app()->getLocale() === 'id' ? 'Reset' : 'Reset' }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- WIN MODAL --}}
    <template x-if="showWinModal">
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-kid shadow-2xl p-8 text-center max-w-md w-full">
                <x-lucide-party-popper class="w-20 h-20 mx-auto mb-3 text-kid-blue" stroke-width="1.5" />
                <h2 class="text-3xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Hebat!' : 'Great job!' }}
                </h2>
                <div class="flex justify-center gap-2 my-4">
                    <template x-for="i in starsEarned" :key="i">
                        <x-lucide-star class="w-10 h-10 text-kid-yellow fill-current" stroke-width="1.5" />
                    </template>
                </div>
                <p class="text-gray-500 mb-6">
                    {{ app()->getLocale() === 'id' ? 'Kamu memperbaiki programnya!' : 'You fixed the program!' }}
                </p>
                <div class="flex flex-col gap-3">
                    @if($nextLevel)
                        <a href="{{ $nextLevel->playRoute() }}"
                           class="btn-kid bg-kid-blue text-white text-lg">
                            <span>{{ app()->getLocale() === 'id' ? 'Level Berikutnya' : 'Next Level' }}</span>
                            <x-lucide-arrow-right class="w-5 h-5 ml-2" stroke-width="2.5" />
                        </a>
                    @endif
                    <a href="{{ route('student.dashboard') }}"
                       class="btn-kid bg-kid-mint text-white">
                        {{ app()->getLocale() === 'id' ? 'Kembali ke Dasbor' : 'Back to Dashboard' }}
                    </a>
                </div>
            </div>
        </div>
    </template>

    {{-- FAIL MODAL --}}
    <template x-if="showFailModal">
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-kid shadow-2xl p-8 text-center max-w-md w-full">
                <x-lucide-frown class="w-20 h-20 mx-auto mb-3 text-kid-coral" stroke-width="1.5" />
                <h2 class="text-3xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Belum Berhasil' : 'Not Yet!' }}
                </h2>
                <p class="text-gray-500 mb-6">
                    {{ app()->getLocale() === 'id'
                        ? 'Coba klik blok lain yang menurutmu salah.'
                        : 'Try clicking a different block that you think is wrong.' }}
                </p>
                <button @click="dismissFail()"
                        class="btn-kid bg-kid-coral text-white w-full text-lg">
                    {{ app()->getLocale() === 'id' ? 'Coba Lagi' : 'Try Again' }}
                </button>
            </div>
        </div>
    </template>
</div>