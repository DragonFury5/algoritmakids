<div class="max-w-5xl mx-auto pb-24 lg:pb-0"
     @resize.window="windowWidth = window.innerWidth"
     x-data="robotGame({
        width: {{ $level->game_data['width'] }},
        height: {{ $level->game_data['height'] }},
        start: {{ Js::from($level->game_data['start']) }},
        goal: {{ Js::from($level->game_data['goal']) }},
        walls: {{ Js::from($level->game_data['walls']) }},
        max_blocks: {{ $level->game_data['max_blocks'] ?? 20 }},
        allow_loops: {{ !empty($level->game_data['allow_loops']) ? 'true' : 'false' }},
        allow_conditionals: {{ !empty($level->game_data['allow_conditionals']) ? 'true' : 'false' }},
        level_id: {{ $level->id }}

     })">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('student.dashboard') }}" class="text-kid-blue font-bold inline-flex items-center gap-1 hover:underline">
            <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
            <span>{{ __('ui.dashboard') }}</span>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold mt-2">{{ $level->title }}</h1>
        <p class="text-gray-600 mt-1">{{ $level->instructions }}</p>
    </div>

    <div class="grid lg:grid-cols-[auto_1fr] gap-4 lg:gap-6">

        {{-- Grid --}}
        <div class="bg-white rounded-kid shadow-md p-2 md:p-4">
            <div class="overflow-x-auto">
    <div class="relative shrink-0 mx-auto w-fit"
                     :style="`width: ${width * cell}px; height: ${height * cell}px;
                              background-color: #FFF8F0;
                              background-image:
                                linear-gradient(to right, #E5D9C9 1px, transparent 1px),
                                linear-gradient(to bottom, #E5D9C9 1px, transparent 1px);
                              background-size: ${cell}px ${cell}px;`">

                    <template x-for="wall in walls" :key="wall">
                        <div class="absolute rounded-md"
                             :style="`width: ${cell}px; height: ${cell}px;
                                      left: ${wall.split(',')[0] * cell}px;
                                      top: ${wall.split(',')[1] * cell}px;
                                      background-color: #2D2A26;`"></div>
                    </template>

                    <div class="absolute flex items-center justify-center"
                         :style="`width: ${cell}px; height: ${cell}px;
                                  left: ${goal.x * cell}px; top: ${goal.y * cell}px;`">
                        <x-lucide-flag class="w-8 h-8 md:w-9 md:h-9 text-kid-blue" stroke-width="2.5" />
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
            </div>

            <p x-show="needsScroll" class="text-xs text-gray-400 text-center mt-2 italic">
                {{ app()->getLocale() === 'id' ? '← Geser untuk melihat seluruh peta →' : '← Swipe to see the whole map →' }}
            </p>
        </div>

        {{-- Right side --}}
        <div class="space-y-4">

            {{-- Palette --}}
            <div class="bg-white rounded-kid shadow-md p-4">
                <h2 class="font-bold mb-3">{{ app()->getLocale() === 'id' ? 'Blok Perintah' : 'Command Blocks' }}</h2>
                <div class="flex flex-wrap gap-3">
                    <button @click="addBlock('forward')" :disabled="running || won"
                            class="btn-kid bg-kid-blue text-white gap-2">
                        <x-lucide-arrow-right class="w-5 h-5" stroke-width="2.5" />
                        <span>{{ app()->getLocale() === 'id' ? 'Maju' : 'Move' }}</span>
                    </button>
                    <button @click="addBlock('left')" :disabled="running || won"
                            class="btn-kid bg-kid-purple text-white gap-2">
                        <x-lucide-rotate-ccw class="w-5 h-5" stroke-width="2.5" />
                        <span>{{ app()->getLocale() === 'id' ? 'Kiri' : 'Left' }}</span>
                    </button>
                    <button @click="addBlock('right')" :disabled="running || won"
                            class="btn-kid bg-kid-mint text-white gap-2">
                        <x-lucide-rotate-cw class="w-5 h-5" stroke-width="2.5" />
                        <span>{{ app()->getLocale() === 'id' ? 'Kanan' : 'Right' }}</span>
                    </button>
                  <template x-if="allowLoops">
    <button @click="addBlock('loop')" :disabled="running || won"
            class="btn-kid bg-kid-yellow text-kid-text gap-2">
        <x-lucide-repeat class="w-5 h-5" stroke-width="2.5" />
        <span>{{ app()->getLocale() === 'id' ? 'Ulangi' : 'Repeat' }}</span>
    </button>
</template>
<template x-if="allowConditionals">
    <button @click="addBlock('if_empty')" :disabled="running || won"
            class="btn-kid bg-white border-2 border-kid-mint text-kid-mint gap-2">
        <x-lucide-check-circle class="w-5 h-5" stroke-width="2.5" />
        <span>{{ app()->getLocale() === 'id' ? 'Jika Kosong' : 'If Clear' }}</span>
    </button>
</template>
<template x-if="allowConditionals">
    <button @click="addBlock('if_wall')" :disabled="running || won"
            class="btn-kid bg-white border-2 border-kid-coral text-kid-coral gap-2">
        <x-lucide-x-circle class="w-5 h-5" stroke-width="2.5" />
        <span>{{ app()->getLocale() === 'id' ? 'Jika Dinding' : 'If Wall' }}</span>
    </button>
</template>
                </div>
            </div>

            {{-- Program --}}
            <div class="bg-white rounded-kid shadow-md p-4 min-h-[200px]">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold">{{ app()->getLocale() === 'id' ? 'Program Anda' : 'Your Program' }}</h2>
                    <span class="text-xs text-gray-400">
                        {{ app()->getLocale() === 'id' ? 'Klik area untuk fokus' : 'Click an area to focus' }}
                    </span>
                </div>

                <div @click="focusMain()"
                     class="flex flex-wrap gap-2 p-4 rounded-kid transition cursor-pointer min-h-[100px]"
                     :class="focusedContainer === 'main'
                        ? 'bg-kid-blue/10 ring-2 ring-kid-blue'
                        : 'bg-kid-bg ring-1 ring-gray-200'">

                    <template x-for="(block, i) in program" :key="i">
                        <div>
                           <template x-if="['forward', 'left', 'right'].includes(block.type)">
    <button @click.stop="removeBlock(i)" :disabled="running"
            class="animate-slide-up px-4 py-3 rounded-kid font-extrabold text-white shadow-sm flex items-center justify-center"
            :class="{
                'bg-kid-blue': block.type === 'forward',
                'bg-kid-purple': block.type === 'left',
                'bg-kid-mint': block.type === 'right',
            }">
        <span x-show="block.type === 'forward'"><x-lucide-arrow-right class="w-5 h-5" stroke-width="2.5" /></span>
        <span x-show="block.type === 'left'"><x-lucide-rotate-ccw class="w-5 h-5" stroke-width="2.5" /></span>
        <span x-show="block.type === 'right'"><x-lucide-rotate-cw class="w-5 h-5" stroke-width="2.5" /></span>
    </button>
</template>

                            <template x-if="block.type === 'if_empty'">
    <div @click.stop="focusLoop(i)"
         class="animate-slide-up border-4 rounded-kid p-3 bg-kid-mint/10 transition cursor-pointer"
         :class="focusedContainer === String(i)
            ? 'border-kid-mint ring-4 ring-kid-mint/30'
            : 'border-kid-mint/50'">

        <div class="flex items-center gap-2 mb-2">
            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
            <span class="font-extrabold text-sm text-kid-text">
                {{ app()->getLocale() === 'id' ? 'Jika Kosong →' : 'If Clear →' }}
            </span>
            <button @click.stop="removeBlock(i)" :disabled="running"
                    class="ml-auto text-kid-coral font-bold flex items-center justify-center">
                <x-lucide-x class="w-4 h-4" stroke-width="3" />
            </button>
        </div>

        <div class="flex flex-wrap gap-2 min-h-[44px]">
            <template x-for="(inner, j) in block.body" :key="j">
                <button @click.stop="removeLoopBlock(i, j)" :disabled="running"
                        class="px-3 py-2 rounded-kid text-sm font-extrabold text-white shadow-sm flex items-center justify-center"
                        :class="{
                            'bg-kid-blue': inner.type === 'forward',
                            'bg-kid-purple': inner.type === 'left',
                            'bg-kid-mint': inner.type === 'right',
                        }">
                    <span x-show="inner.type === 'forward'"><x-lucide-arrow-right class="w-4 h-4" stroke-width="2.5" /></span>
                    <span x-show="inner.type === 'left'"><x-lucide-rotate-ccw class="w-4 h-4" stroke-width="2.5" /></span>
                    <span x-show="inner.type === 'right'"><x-lucide-rotate-cw class="w-4 h-4" stroke-width="2.5" /></span>
                </button>
            </template>
            <p x-show="block.body.length === 0" class="text-xs text-gray-400 italic">
                {{ app()->getLocale() === 'id' ? 'Lakukan ini jika jalan kosong' : 'Do this if path is clear' }}
            </p>
        </div>
    </div>
</template>

<template x-if="block.type === 'if_wall'">
    <div @click.stop="focusLoop(i)"
         class="animate-slide-up border-4 rounded-kid p-3 bg-kid-coral/10 transition cursor-pointer"
         :class="focusedContainer === String(i)
            ? 'border-kid-coral ring-4 ring-kid-coral/30'
            : 'border-kid-coral/50'">

        <div class="flex items-center gap-2 mb-2">
            <x-lucide-x-circle class="w-4 h-4 text-kid-coral" stroke-width="2.5" />
            <span class="font-extrabold text-sm text-kid-text">
                {{ app()->getLocale() === 'id' ? 'Jika Dinding →' : 'If Wall →' }}
            </span>
            <button @click.stop="removeBlock(i)" :disabled="running"
                    class="ml-auto text-kid-coral font-bold flex items-center justify-center">
                <x-lucide-x class="w-4 h-4" stroke-width="3" />
            </button>
        </div>

        <div class="flex flex-wrap gap-2 min-h-[44px]">
            <template x-for="(inner, j) in block.body" :key="j">
                <button @click.stop="removeLoopBlock(i, j)" :disabled="running"
                        class="px-3 py-2 rounded-kid text-sm font-extrabold text-white shadow-sm flex items-center justify-center"
                        :class="{
                            'bg-kid-blue': inner.type === 'forward',
                            'bg-kid-purple': inner.type === 'left',
                            'bg-kid-mint': inner.type === 'right',
                        }">
                    <span x-show="inner.type === 'forward'"><x-lucide-arrow-right class="w-4 h-4" stroke-width="2.5" /></span>
                    <span x-show="inner.type === 'left'"><x-lucide-rotate-ccw class="w-4 h-4" stroke-width="2.5" /></span>
                    <span x-show="inner.type === 'right'"><x-lucide-rotate-cw class="w-4 h-4" stroke-width="2.5" /></span>
                </button>
            </template>
            <p x-show="block.body.length === 0" class="text-xs text-gray-400 italic">
                {{ app()->getLocale() === 'id' ? 'Lakukan ini jika ada dinding' : 'Do this if there is a wall' }}
            </p>
        </div>
    </div>
</template>

                            <template x-if="block.type === 'loop'">
                                <div @click.stop="focusLoop(i)"
                                     class="animate-slide-up border-4 rounded-kid p-3 bg-kid-yellow/15 transition cursor-pointer"
                                     :class="focusedContainer === String(i)
                                        ? 'border-kid-yellow ring-4 ring-kid-yellow/50'
                                        : 'border-kid-yellow/70'">

                                    <div class="flex items-center gap-2 mb-2">
                                        <x-lucide-repeat class="w-4 h-4" stroke-width="2.5" />
                                        <button @click.stop="setLoopCount(i, -1)" :disabled="running"
                                                class="w-7 h-7 rounded-full bg-kid-yellow font-extrabold text-kid-text leading-none flex items-center justify-center">
                                            <x-lucide-minus class="w-3 h-3" stroke-width="3" />
                                        </button>
                                        <span class="font-extrabold text-lg" x-text="block.count"></span>
                                        <button @click.stop="setLoopCount(i, 1)" :disabled="running"
                                                class="w-7 h-7 rounded-full bg-kid-yellow font-extrabold text-kid-text leading-none flex items-center justify-center">
                                            <x-lucide-plus class="w-3 h-3" stroke-width="3" />
                                        </button>
                                        <button @click.stop="removeBlock(i)" :disabled="running"
                                                class="ml-auto text-kid-coral font-bold flex items-center justify-center">
                                            <x-lucide-x class="w-4 h-4" stroke-width="3" />
                                        </button>
                                    </div>

                                    <div class="flex flex-wrap gap-2 min-h-[44px]">
                                        <template x-for="(inner, j) in block.body" :key="j">
                                            <button @click.stop="removeLoopBlock(i, j)" :disabled="running"
                                                    class="px-3 py-2 rounded-kid text-sm font-extrabold text-white shadow-sm flex items-center justify-center"
                                                    :class="{
                                                        'bg-kid-blue': inner.type === 'forward',
                                                        'bg-kid-purple': inner.type === 'left',
                                                        'bg-kid-mint': inner.type === 'right',
                                                    }">
                                                <span x-show="inner.type === 'forward'"><x-lucide-arrow-right class="w-4 h-4" stroke-width="2.5" /></span>
                                                <span x-show="inner.type === 'left'"><x-lucide-rotate-ccw class="w-4 h-4" stroke-width="2.5" /></span>
                                                <span x-show="inner.type === 'right'"><x-lucide-rotate-cw class="w-4 h-4" stroke-width="2.5" /></span>
                                            </button>
                                        </template>
                                        <p x-show="block.body.length === 0" class="text-xs text-gray-400 italic">
                                            {{ app()->getLocale() === 'id' ? 'Klik blok untuk menambah di sini' : 'Click blocks to add here' }}
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <p x-show="program.length === 0" class="text-gray-400 italic">
                        {{ app()->getLocale() === 'id' ? 'Klik blok untuk menambah.' : 'Click blocks to add.' }}
                    </p>
                </div>
            </div>

            {{-- Controls — fixed bar on mobile --}}
            <div class="fixed lg:static bottom-0 left-0 right-0 z-30 bg-kid-bg/95 backdrop-blur border-t border-gray-200 p-3 lg:bg-transparent lg:border-0 lg:backdrop-blur-none lg:z-auto lg:p-0">
                <div class="flex gap-3 max-w-md mx-auto lg:max-w-none">
                    <button @click="run()"
                            :disabled="running || won || program.length === 0"
                            class="btn-kid flex-1 text-lg gap-2 transition"
                            :class="(running || won || program.length === 0)
                                ? 'bg-gray-200 text-gray-400'
                                : 'bg-kid-blue text-white'">
                        <x-lucide-play class="w-5 h-5 fill-current" stroke-width="2" />
                        <span>{{ app()->getLocale() === 'id' ? 'Jalankan' : 'Run' }}</span>
                    </button>
                    <button @click="clearProgram()" :disabled="running"
                            class="btn-kid bg-white border-2 border-gray-200 text-kid-text flex-1 gap-2">
                        <x-lucide-trash-2 class="w-5 h-5" stroke-width="2.5" />
                        <span>{{ app()->getLocale() === 'id' ? 'Hapus' : 'Clear' }}</span>
                    </button>
                </div>
            </div>

            @if($previousStars > 0)
                <div class="text-sm text-gray-500 text-center flex items-center justify-center gap-2">
                    <span>{{ app()->getLocale() === 'id' ? 'Bintang terbaik:' : 'Best stars:' }}</span>
                    <span class="inline-flex gap-0.5">
                        @for($i = 0; $i < $previousStars; $i++)
                            <x-lucide-star class="w-4 h-4 text-kid-yellow fill-current" stroke-width="1.5" />
                        @endfor
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- WIN MODAL --}}
    <template x-if="showWinModal">
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-8 text-center max-w-md w-full">
                <x-lucide-party-popper class="w-20 h-20 mx-auto mb-3 text-kid-blue" stroke-width="1.5" />
                <h2 class="text-3xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Hebat!' : 'Great job!' }}
                </h2>
                <div class="flex justify-center gap-2 my-4">
                    <template x-for="i in starsEarned" :key="i">
                        <div class="animate-pop-in" :style="`animation-delay: ${(i - 1) * 120}ms`">
                            <x-lucide-star class="w-10 h-10 text-kid-yellow fill-current" stroke-width="1.5" />
                        </div>
                    </template>
                </div>
                <p class="text-gray-500 mb-6">
                    {{ app()->getLocale() === 'id' ? 'Kamu berhasil menyelesaikan level!' : 'You completed the level!' }}
                </p>
                <div class="flex flex-col gap-3">
                    @if($nextLevel)
                        <a href="{{ $nextLevel->playRoute() }}"
                           class="btn-kid bg-kid-blue text-white text-lg gap-2">
                            <span>{{ app()->getLocale() === 'id' ? 'Level Berikutnya' : 'Next Level' }}</span>
                            <x-lucide-arrow-right class="w-5 h-5" stroke-width="2.5" />
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
            <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-8 text-center max-w-md w-full">
                <x-lucide-frown class="w-20 h-20 mx-auto mb-3 text-kid-coral" stroke-width="1.5" />
                <h2 class="text-3xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Belum Berhasil' : 'Not Yet!' }}
                </h2>
                <p class="text-gray-500 mb-6">
                    {{ app()->getLocale() === 'id'
                        ? 'Robot belum mencapai bendera. Ayo coba lagi!'
                        : 'The robot didn\'t reach the flag. Try again!' }}
                </p>
                <button @click="dismissFail()"
                        class="btn-kid bg-kid-blue text-white w-full text-lg">
                    {{ app()->getLocale() === 'id' ? 'Coba Lagi' : 'Try Again' }}
                </button>
            </div>
        </div>
    </template>
</div>