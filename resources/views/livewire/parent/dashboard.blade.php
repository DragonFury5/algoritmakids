<div>
    {{-- ==================== HEADER ==================== --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold">
                {{ app()->getLocale() === 'id' ? 'Halo' : 'Hello' }}, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-500 mt-1">
                {{ app()->getLocale() === 'id'
                    ? 'Kelola akun anak Anda di sini.'
                    : 'Manage your children\'s accounts here.' }}
            </p>
        </div>

        <livewire:parent.add-child />
    </div>

    {{-- ==================== SECTION TITLE ==================== --}}
    <h2 class="text-xl font-bold mb-4">
        {{ app()->getLocale() === 'id' ? 'Anak-Anak Anda' : 'Your Children' }}
    </h2>

    {{-- ==================== EMPTY STATE ==================== --}}
    @if($children->isEmpty())
        <div class="bg-white rounded-kid shadow-md p-10 text-center">
            <x-lucide-user-plus class="w-16 h-16 mx-auto mb-4 text-kid-coral" stroke-width="1.5" />
            <p class="text-gray-500">
                {{ app()->getLocale() === 'id'
                    ? 'Belum ada akun anak. Klik "Tambah Anak" untuk membuat.'
                    : 'No child accounts yet. Click "Add Child" to create one.' }}
            </p>
        </div>
    @else
        {{-- ==================== CHILDREN GRID ==================== --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($children as $child)
                <div class="bg-white rounded-kid shadow-md p-6">
                    <div class="flex items-center gap-4 mb-4">
                        @php
                            $colors = [
                                'robot-blue'   => '#7EC8E3',
                                'robot-green'  => '#A8E6CF',
                                'robot-yellow' => '#FFD97D',
                                'robot-coral'  => '#FF8B94',
                                'robot-purple' => '#B39DDB',
                                'robot-mint'   => '#B8E0D2',
                            ];
                            $bg = $colors[$child->avatar] ?? '#7EC8E3';
                        @endphp

                        <div class="w-16 h-16 rounded-full flex items-center justify-center"
                             style="background-color: {{ $bg }}">
                            <x-lucide-bot class="w-8 h-8 text-white" stroke-width="2" />
                        </div>

                        <div>
                            <h3 class="font-extrabold text-lg">{{ $child->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $child->email }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-sm text-gray-600 mb-4">
                        <span class="flex items-center gap-1">
                            <x-lucide-star class="w-4 h-4 text-kid-yellow fill-current" stroke-width="1.5" />
                            {{ $child->total_stars }}
                            {{ app()->getLocale() === 'id' ? 'bintang' : 'stars' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <x-lucide-flag class="w-4 h-4 text-kid-coral" stroke-width="2" />
                            {{ $child->progress_count }}
                            {{ app()->getLocale() === 'id' ? 'level' : 'levels' }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-400 italic">
                        {{ app()->getLocale() === 'id'
                            ? 'Detail progress akan segera hadir.'
                            : 'Detailed progress coming soon.' }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>