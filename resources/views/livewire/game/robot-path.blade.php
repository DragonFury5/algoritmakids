<div class="max-w-5xl mx-auto"
     x-data="robotGame({
        width: {{ $level->game_data['width'] }},
        height: {{ $level->game_data['height'] }},
        start: {{ Js::from($level->game_data['start']) }},
        goal: {{ Js::from($level->game_data['goal']) }},
        walls: {{ Js::from($level->game_data['walls']) }},
        max_blocks: {{ $level->game_data['max_blocks'] ?? 20 }},
        allow_loops: {{ !empty($level->game_data['allow_loops']) ? 'true' : 'false' }},
        level_id: {{ $level->id }}
     })">

    <div class="mb-4">
        <a href="{{ route('student.dashboard') }}" class="text-kid-coral font-bold">← {{ __('ui.dashboard') }}</a>
        <h1 class="text-2xl md:text-3xl font-extrabold mt-2">{{ $level->title }}</h1>
        <p class="text-gray-600 mt-1">{{ $level->instructions }}</p>
    </div>

    <div class="grid md:grid-cols-[auto_1fr] gap-6">

        {{-- Grid --}}
        <div class="bg-white rounded-kid shadow-md p-4">
            <div class="relative mx-auto"
                 :style="`width: ${width * cell}px; height: ${height * cell}px;
                          background-color: #F7FBFF;
                          background-image:
                            linear-gradient(to right, #64748B 1px, transparent 1px),
                            linear-gradient(to bottom, #64748B 1px, transparent 1px);
                          background-size: ${cell}px ${cell}px;`">

                <template x-for="wall in walls" :key="wall">
                    <div class="absolute rounded-md"
                         :style="`width: ${cell}px; height: ${cell}px;
                                  left: ${wall.split(',')[0] * cell}px;
                                  top: ${wall.split(',')[1] * cell}px;
                                  background-color: #4B5563;`"></div>
                </template>

                <div class="absolute flex items-center justify-center text-3xl"
                     :style="`width: ${cell}px; height: ${cell}px;
                              left: ${goal.x * cell}px; top: ${goal.y * cell}px;`">
                    🏁
                </div>

                <div class="absolute transition-transform duration-300 ease-out"
                     :style="`width: ${cell}px; height: ${cell}px; ${robotStyle}`">
                    <div class="w-full h-full transition-transform duration-200"
                         :style="robotRotation">
                        <svg viewBox="0 0 64 64" class="w-full h-full">
                            {{-- Direction arrow: big, bright, obvious --}}
                            <polygon points="62,32 46,20 46,44"
                                     fill="#FFD97D"
                                     stroke="#FF8B94"
                                     stroke-width="2"
                                     stroke-linejoin="round"/>
                            {{-- Antenna --}}
                            <line x1="32" y1="18" x2="32" y2="6" stroke="#2E3A59" stroke-width="2"/>
                            <circle cx="32" cy="6" r="3" fill="#FF8B94"/>
                            {{-- Body --}}
                            <rect x="14" y="18" width="36" height="32" rx="10"
                                  fill="#7EC8E3" stroke="#2E3A59" stroke-width="1.5"/>
                            {{-- Eyes --}}
                            <circle cx="25" cy="32" r="4" fill="white"/>
                            <circle cx="39" cy="32" r="4" fill="white"/>
                            <circle cx="25" cy="32" r="2" fill="#2E3A59"/>
                            <circle cx="39" cy="32" r="2" fill="#2E3A59"/>
                            {{-- Smile --}}
                            <path d="M 26 42 Q 32 46 38 42"
                                  stroke="#2E3A59" stroke-width="2" fill="none"
                                  stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right side --}}
        <div class="space-y-4">

            {{-- Palette --}}
            <div class="bg-white rounded-kid shadow-md p-4">
                <h2 class="font-bold mb-3">{{ app()->getLocale() === 'id' ? 'Blok Perintah' : 'Command Blocks' }}</h2>
                <div class="flex flex-wrap gap-3">
                    <button @click="addBlock('forward')" :disabled="running || won"
                            class="btn-kid bg-kid-teal text-white">
                        ➡️ {{ app()->getLocale() === 'id' ? 'Maju' : 'Move' }}
                    </button>
                    <button @click="addBlock('left')" :disabled="running || won"
                            class="btn-kid bg-kid-purple text-white">
                        ↺ {{ app()->getLocale() === 'id' ? 'Kiri' : 'Left' }}
                    </button>
                    <button @click="addBlock('right')" :disabled="running || won"
                            class="btn-kid bg-kid-teal text-white">
                        ↻ {{ app()->getLocale() === 'id' ? 'Kanan' : 'Right' }}
                    </button>
                    <template x-if="allowLoops">
                        <button @click="addBlock('loop')" :disabled="running || won"
                                class="btn-kid bg-kid-yellow text-kid-text">
                            🔁 {{ app()->getLocale() === 'id' ? 'Ulangi' : 'Repeat' }}
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
                        ? 'bg-kid-teal/15 ring-2 ring-kid-coral'
                        : 'bg-kid-bg ring-1 ring-gray-200'">

                    <template x-for="(block, i) in program" :key="i">
                        <div>
                            <template x-if="block.type !== 'loop'">
                                <button @click.stop="removeBlock(i)" :disabled="running"
                                        class="px-5 py-3 rounded-kid font-extrabold text-white shadow-sm"
                                        :class="{
                                            'bg-kid-teal': block.type === 'forward',
                                            'bg-kid-purple': block.type === 'left',
                                            'bg-kid-teal': block.type === 'right',
                                        }">
                                    <span x-show="block.type === 'forward'">➡️</span>
                                    <span x-show="block.type === 'left'">↺</span>
                                    <span x-show="block.type === 'right'">↻</span>
                                </button>
                            </template>

                            <template x-if="block.type === 'loop'">
                                <div @click.stop="focusLoop(i)"
                                     class="border-4 rounded-kid p-3 bg-kid-yellow/15 transition cursor-pointer"
                                     :class="focusedContainer === String(i)
                                        ? 'border-kid-yellow ring-4 ring-kid-yellow/50'
                                        : 'border-kid-yellow/70'">

                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-bold text-sm">🔁</span>
                                        <button @click.stop="setLoopCount(i, -1)" :disabled="running"
                                                class="w-7 h-7 rounded-full bg-kid-yellow font-extrabold text-kid-text leading-none">−</button>
                                        <span class="font-extrabold text-lg" x-text="block.count"></span>
                                        <button @click.stop="setLoopCount(i, 1)" :disabled="running"
                                                class="w-7 h-7 rounded-full bg-kid-yellow font-extrabold text-kid-text leading-none">+</button>
                                        <button @click.stop="removeBlock(i)" :disabled="running"
                                                class="ml-auto text-kid-coral text-sm font-bold">✕</button>
                                    </div>

                                    <div class="flex flex-wrap gap-2 min-h-[44px]">
                                        <template x-for="(inner, j) in block.body" :key="j">
                                            <button @click.stop="removeLoopBlock(i, j)" :disabled="running"
                                                    class="px-4 py-2 rounded-kid text-sm font-extrabold text-white shadow-sm"
                                                    :class="{
                                                        'bg-kid-teal': inner.type === 'forward',
                                                        'bg-kid-purple': inner.type === 'left',
                                                        'bg-kid-teal': inner.type === 'right',
                                                    }">
                                                <span x-show="inner.type === 'forward'">➡️</span>
                                                <span x-show="inner.type === 'left'">↺</span>
                                                <span x-show="inner.type === 'right'">↻</span>
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

            {{-- Controls --}}
            <div class="flex gap-3">
                <button @click="run()"
                        :disabled="running || won || program.length === 0"
                        class="btn-kid flex-1 text-lg transition"
                        :class="(running || won || program.length === 0)
                            ? 'bg-gray-200 text-gray-400'
                            : 'bg-kid-teal text-white'">
                    ▶ {{ app()->getLocale() === 'id' ? 'Jalankan' : 'Run' }}
                </button>
                <button @click="clearProgram()" :disabled="running"
                        class="btn-kid bg-kid-teal text-white flex-1">
                    🗑 {{ app()->getLocale() === 'id' ? 'Hapus' : 'Clear' }}
                </button>
            </div>

            @if($previousStars > 0)
                <div class="text-sm text-gray-500 text-center">
                    {{ app()->getLocale() === 'id' ? 'Bintang terbaik:' : 'Best stars:' }}
                    <span class="text-kid-yellow">{{ str_repeat('⭐', $previousStars) }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ==================== WIN MODAL ==================== --}}
    <template x-if="showWinModal">
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-kid shadow-2xl p-8 text-center max-w-md w-full">
                <div class="text-6xl mb-3">🎉</div>
                <h2 class="text-3xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Hebat!' : 'Great job!' }}
                </h2>
                <div class="text-4xl my-4" x-text="'⭐'.repeat(starsEarned)"></div>
                <p class="text-gray-500 mb-6">
                    {{ app()->getLocale() === 'id' ? 'Kamu berhasil menyelesaikan level!' : 'You completed the level!' }}
                </p>
                <div class="flex flex-col gap-3">
                    @if($nextLevel)
                        <a href="{{ route('play', $nextLevel) }}"
                           class="btn-kid bg-kid-teal text-white text-lg">
                            {{ app()->getLocale() === 'id' ? 'Level Berikutnya' : 'Next Level' }} →
                        </a>
                    @endif
                    <a href="{{ route('student.dashboard') }}"
                       class="btn-kid bg-kid-teal text-white">
                        {{ app()->getLocale() === 'id' ? 'Kembali ke Dasbor' : 'Back to Dashboard' }}
                    </a>
                </div>
            </div>
        </div>
    </template>

    {{-- ==================== FAIL MODAL ==================== --}}
    <template x-if="showFailModal">
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-kid shadow-2xl p-8 text-center max-w-md w-full">
                <div class="text-6xl mb-3">😅</div>
                <h2 class="text-3xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Belum Berhasil' : 'Not Yet!' }}
                </h2>
                <p class="text-gray-500 mb-6">
                    {{ app()->getLocale() === 'id'
                        ? 'Robot belum mencapai bendera. Ayo coba lagi!'
                        : 'The robot didn\'t reach the flag. Try again!' }}
                </p>
                <button @click="dismissFail()"
                        class="btn-kid bg-kid-teal text-white w-full text-lg">
                    {{ app()->getLocale() === 'id' ? 'Coba Lagi' : 'Try Again' }}
                </button>
            </div>
        </div>
    </template>
</div>