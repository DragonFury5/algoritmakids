<div>
    <div class="bg-white rounded-kid shadow-md p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-kid-teal flex items-center justify-center text-3xl">🤖</div>
            <div>
                <h1 class="text-2xl font-extrabold">
                    {{ app()->getLocale() === 'id' ? 'Hai' : 'Hi' }}, {{ auth()->user()->name }}!
                </h1>
                <p class="text-gray-500">
                    {{ app()->getLocale() === 'id' ? 'Ayo belajar coding hari ini!' : 'Let\'s learn coding today!' }}
                </p>
            </div>
        </div>

        <div class="flex gap-6 text-lg">
            <span>⭐ <strong>{{ $totalStars }}</strong></span>
            <span>🏅 <strong>{{ $badgeCount }}</strong></span>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4">
        {{ app()->getLocale() === 'id' ? 'Modul Belajar' : 'Learning Modules' }}
    </h2>

    @if($modules->isEmpty())
        <div class="bg-white rounded-kid shadow-md p-10 text-center text-gray-500">
            {{ app()->getLocale() === 'id' ? 'Belum ada modul. Silakan hubungi admin.' : 'No modules yet. Contact admin.' }}
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($modules as $i => $module)
                <div class="bg-white rounded-kid shadow-md p-6">
                    <div class="w-12 h-12 rounded-full bg-kid-purple text-white flex items-center justify-center font-extrabold text-xl mb-4">
                        {{ $i + 1 }}
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ $module->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $module->description }}</p>
                    <p class="text-xs text-gray-400 mb-4">
                        {{ $module->levels->count() }} {{ app()->getLocale() === 'id' ? 'level' : 'levels' }}
                    </p>
                    @php $firstLevel = $module->levels->first(); @endphp
@if($firstLevel)
    <a href="{{ route('play', $firstLevel) }}"
       class="btn-kid bg-kid-teal text-white w-full">
        {{ app()->getLocale() === 'id' ? 'Mulai' : 'Start' }}
    </a>
@else
    <button class="btn-kid bg-gray-300 text-white w-full" disabled>
        {{ app()->getLocale() === 'id' ? 'Segera Hadir' : 'Coming Soon' }}
    </button>
@endif
                </div>
            @endforeach
        </div>
    @endif
</div>