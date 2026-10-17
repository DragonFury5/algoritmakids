<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('teacher.dashboard') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
            <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Kembali' : 'Back' }}</span>
        </a>
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold">{{ $classroom->name }}</h1>
                <p class="text-gray-500 mt-1">
                    {{ app()->getLocale() === 'id' ? 'Kode Kelas:' : 'Class Code:' }} 
                    <span class="font-mono bg-kid-bg px-2 py-0.5 rounded text-kid-text font-bold border border-gray-200">{{ $classroom->code }}</span>
                </p>
            </div>
            <div class="bg-kid-blue/10 px-4 py-2 rounded-kid text-kid-blue font-bold text-sm">
                {{ $students->count() }} {{ app()->getLocale() === 'id' ? 'Siswa' : 'Students' }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded-kid border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-extrabold text-lg flex items-center gap-2">
                <x-lucide-trophy class="w-5 h-5 text-kid-yellow fill-current" stroke-width="2" />
                {{ app()->getLocale() === 'id' ? 'Papan Peringkat' : 'Leaderboard' }}
            </h2>
        </div>

        @if($students->isEmpty())
            <div class="p-10 text-center text-gray-500">
                {{ app()->getLocale() === 'id' ? 'Belum ada siswa yang bergabung. Bagikan kode kelas!' : 'No students joined yet. Share the class code!' }}
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($students as $index => $student)
                    @php
                        $avatarColors = [
                            'robot-blue'   => '#7EC8E3', 'robot-green'  => '#A8E6CF',
                            'robot-yellow' => '#FFD97D', 'robot-coral'  => '#FF8B94',
                            'robot-purple' => '#B39DDB', 'robot-mint'   => '#B8E0D2',
                        ];
                        $bg = $avatarColors[$student->avatar] ?? '#7EC8E3';
                    @endphp
                    <div class="flex items-center gap-4 p-4 hover:bg-kid-bg/40 transition">
                        
                        {{-- Rank --}}
                        <div class="w-8 text-center font-extrabold text-lg shrink-0
                                    {{ $index === 0 ? 'text-kid-yellow' : ($index === 1 ? 'text-gray-400' : ($index === 2 ? 'text-orange-400' : 'text-gray-300')) }}">
                            #{{ $index + 1 }}
                        </div>

                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background-color: {{ $bg }}">
                            <x-lucide-bot class="w-5 h-5 text-white" stroke-width="2" />
                        </div>

                        {{-- Name & Tier --}}
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-sm truncate">{{ $student->name }}</div>
                            <div class="text-xs text-gray-500 capitalize">{{ $student->tier }}</div>
                        </div>

                        {{-- Stars --}}
                        <div class="flex items-center gap-1 font-extrabold text-kid-yellow shrink-0">
                            {{ $student->total_stars }}
                            <x-lucide-star class="w-4 h-4 fill-current" stroke-width="1.5" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>