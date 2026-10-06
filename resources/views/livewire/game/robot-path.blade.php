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
        <a href="{{ route('student.dashboard') }}" class="text-kid-blue font-bold">← {{ __('ui.dashboard') }}</a>
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
                            <rect x="14" y="16" width="36" height="34" rx="10" fill="#7EC8E3"/>
                            <circle cx="25" cy="30" r="4" fill="white"/>
                            <circle cx="39" cy="30" r="4" fill="white"/>
                            <circle cx="25" cy="30" r="2" fill="#2E3A59"/>
                            <circle cx="39" cy="30" r="2" fill="#2E3A59"/>
                            <rect x="24" y="40" width="16" height="3" rx="1.5" fill="#2E3A59"/>
                            <line x1="32" y1="16" x2="32" y2="6" stroke="#2E3A59" stroke-width="2"/>
                            <circle cx="32" cy="6" r="3" fill="#FF8B94"/>
                            <polygon points="58,32 52,26 52,38" fill="#FFD97D"/>
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
                            class="btn-kid bg-kid-blue text-white">
                        ➡️ {{ app()->getLocale() === 'id' ? 'Maju' : 'Move' }}
                    </button>
                    <button @click="addBlock('left')" :disabled="running || won"
                            class="btn-kid bg-kid-purple text-white">
                        ↺ {{ app()->getLocale() === 'id' ? 'Kiri' : 'Left' }}
                    </button>
                    <button @click="addBlock('right')" :disabled="running || won"
                            class="btn-kid bg-kid-coral text-white">
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
            <div class="bg-white rounded-kid shadow-md p-4 min-h-[180px]">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold">{{ app()->getLocale() === 'id' ? 'Program Anda' : 'Your Program' }}</h2>
                    <span class="text-xs text-gray-400">
                        {{ app()->getLocale() === 'id' ? 'Klik area untuk fokus' : 'Click an area to focus' }}
                    </span>
                </div>

                <div @click="focusMain()"
                     class="flex flex-wrap gap-2 p-3 rounded-kid transition cursor-pointer"
                     :class="focusedContainer === 'main'
                        ? 'bg-kid-blue/10 ring-2 ring-kid-blue'
                        : 'bg-kid-bg'">

                    <template x-for="(block, i) in program" :key="i">
                        <div>
                            <template x-if="block.type !== 'loop'">
                                <button @click.stop="removeBlock(i)" :disabled="running"
                                        class="px-4 py-2 rounded-kid font-bold text-white"
                                        :class="{
                                            'bg-kid-blue': block.type === 'forward',
                                            'bg-kid-purple': block.type === 'left',
                                            'bg-kid-coral': block.type === 'right',
                                        }">
                                    <span x-show="block.type === 'forward'">➡️</span>
                                    <span x-show="block.type === 'left'">↺</span>
                                    <span x-show="block.type === 'right'">↻</span>
                                </button>
                            </template>

                            <template x-if="block.type === 'loop'">
                                <div @click.stop="focusLoop(i)"
                                     class="border-4 rounded-kid p-3 bg-kid-yellow/10 transition cursor-pointer"
                                     :class="focusedContainer === String(i)
                                        ? 'border-kid-yellow ring-4 ring-kid-yellow/50'
                                        : 'border-kid-yellow/60'">

                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-bold text-sm">🔁</span>
                                        <button @click.stop="setLoopCount(i, -1)" :disabled="running"
                                                class="w-6 h-6 rounded-full bg-kid-yellow font-extrabold text-kid-text leading-none">−</button>
                                        <span class="font-extrabold text-lg" x-text="block.count"></span>
                                        <button @click.stop="setLoopCount(i, 1)" :disabled="running"
                                                class="w-6 h-6 rounded-full bg-kid-yellow font-extrabold text-kid-text leading-none">+</button>
                                        <button @click.stop="removeBlock(i)" :disabled="running"
                                                class="ml-auto text-kid-coral text-sm font-bold">✕</button>
                                    </div>

                                    <div class="flex flex-wrap gap-2 min-h-[40px]">
                                        <template x-for="(inner, j) in block.body" :key="j">
                                            <button @click.stop="removeLoopBlock(i, j)" :disabled="running"
                                                    class="px-3 py-1 rounded-kid text-sm font-bold text-white"
                                                    :class="{
                                                        'bg-kid-blue': inner.type === 'forward',
                                                        'bg-kid-purple': inner.type === 'left',
                                                        'bg-kid-coral': inner.type === 'right',
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
                <button @click="run()" :disabled="running || won"
                        class="btn-kid bg-kid-mint text-white flex-1 text-lg">
                    ▶ {{ app()->getLocale() === 'id' ? 'Jalankan' : 'Run' }}
                </button>
                <button @click="clearProgram()" :disabled="running"
                        class="btn-kid bg-gray-200 text-gray-700 flex-1">
                    🗑 {{ app()->getLocale() === 'id' ? 'Hapus' : 'Clear' }}
                </button>
            </div>

            {{-- Message --}}
            <template x-if="message">
                <div class="rounded-kid p-4 font-semibold"
                     :class="{
                        'bg-kid-coral/30 text-kid-text': messageType === 'error',
                        'bg-kid-mint/50 text-kid-text': messageType === 'success',
                        'bg-kid-bg text-kid-text': messageType === 'info',
                     }"
                     x-text="message"></div>
            </template>

            {{-- Win overlay --}}
            <template x-if="won">
                <div class="bg-kid-yellow/60 rounded-kid p-6 text-center">
                    <div class="text-5xl mb-2">🎉</div>
                    <div class="text-xl font-extrabold mb-1">
                        {{ app()->getLocale() === 'id' ? 'Hebat!' : 'Great job!' }}
                    </div>
                    <div class="text-2xl mb-4" x-text="'⭐'.repeat(starsEarned)"></div>
                    <div class="flex gap-3 justify-center">
                        @if($nextLevel)
                            <a href="{{ route('play', $nextLevel) }}"
                               class="btn-kid bg-kid-blue text-white">
                                {{ app()->getLocale() === 'id' ? 'Level Berikutnya' : 'Next Level' }} →
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}"
                               class="btn-kid bg-kid-blue text-white">
                                {{ app()->getLocale() === 'id' ? 'Selesai' : 'Finish' }}
                            </a>
                        @endif
                    </div>
                </div>
            </template>

            @if($previousStars > 0)
                <div class="text-sm text-gray-500 text-center">
                    {{ app()->getLocale() === 'id' ? 'Bintang terbaik:' : 'Best stars:' }}
                    <span class="text-kid-yellow">{{ str_repeat('⭐', $previousStars) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>