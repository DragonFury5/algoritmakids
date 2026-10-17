<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('student.dashboard') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
            <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Kembali ke Dasbor' : 'Back to Dashboard' }}</span>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold mt-2">{{ $module->title }}</h1>
        <p class="text-gray-500 mt-1">{{ $module->description }}</p>
    </div>

    @if(session()->has('error'))
        <div class="mb-6 bg-kid-coral/20 text-kid-text rounded-kid p-4 font-bold flex items-center gap-2">
            <x-lucide-lock class="w-5 h-5 text-kid-coral" stroke-width="2.5" />
            {{ session('error') }}
        </div>
    @endif

    {{-- Level Grid --}}
    <div class="grid gap-4">
        @foreach($levels as $i => $level)
            @php
                $isUnlocked = $level->isUnlockedFor(auth()->user());
                $progress = $progressByLevel->get($level->id);
                $stars = $progress?->stars ?? 0;
                $isCompleted = $progress !== null;
            @endphp

            <div class="bg-white rounded-kid border border-gray-200 p-4 md:p-5 flex items-center gap-4
                        {{ $isUnlocked ? 'card-lift' : 'opacity-60 grayscale' }}">
                
                {{-- Number / Lock Icon --}}
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center shrink-0
                            {{ $isCompleted ? 'bg-kid-mint text-white' : ($isUnlocked ? 'bg-kid-blue/10 text-kid-blue' : 'bg-gray-100 text-gray-400') }}">
                    @if(!$isUnlocked)
                        <x-lucide-lock class="w-6 h-6" stroke-width="2.5" />
                    @elseif($isCompleted)
                        <x-lucide-check class="w-6 h-6" stroke-width="3" />
                    @else
                        <span class="font-extrabold text-xl">{{ $i + 1 }}</span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-extrabold text-lg truncate {{ !$isUnlocked ? 'text-gray-500' : '' }}">
                        {{ $level->title }}
                    </h3>
                    <p class="text-sm text-gray-500 truncate">
                        {{ $level->instructions }}
                    </p>
                    @if($isCompleted)
                        <div class="flex gap-0.5 mt-1">
                            @for($s = 1; $s <= 3; $s++)
                                <x-lucide-star class="w-4 h-4 {{ $s <= $stars ? 'text-kid-yellow fill-current' : 'text-gray-200' }}" stroke-width="1.5" />
                            @endfor
                        </div>
                    @endif
                </div>

                {{-- Button --}}
                @if($isUnlocked)
                    <a href="{{ $level->playRoute() }}"
                       class="btn-kid shrink-0 {{ $isCompleted ? 'bg-white border-2 border-gray-200 text-kid-text' : 'bg-kid-blue text-white' }}">
                        @if($isCompleted)
                            <x-lucide-rotate-ccw class="w-4 h-4" stroke-width="2.5" />
                        @else
                            <span class="hidden md:inline">{{ app()->getLocale() === 'id' ? 'Mainkan' : 'Play' }}</span>
                            <x-lucide-play class="w-4 h-4 fill-current md:ml-2" stroke-width="2" />
                        @endif
                    </a>
                @else
                    <div class="shrink-0 px-4 py-3 rounded-kid bg-gray-50 text-gray-400 font-bold text-sm flex items-center gap-2 cursor-not-allowed">
                        <x-lucide-lock class="w-4 h-4" stroke-width="2.5" />
                        <span class="hidden md:inline">{{ app()->getLocale() === 'id' ? 'Terkunci' : 'Locked' }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>