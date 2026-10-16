<div class="max-w-4xl mx-auto">

    {{-- Back + header --}}
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
            <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Anak-Anak Anda' : 'Your Children' }}</span>
        </a>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
            {{ session('success') }}
        </div>
    @endif

    @php
        $avatarColors = [
            'robot-blue'   => '#7EC8E3', 'robot-green'  => '#A8E6CF',
            'robot-yellow' => '#FFD97D', 'robot-coral'  => '#FF8B94',
            'robot-purple' => '#B39DDB', 'robot-mint'   => '#B8E0D2',
        ];
        $avatarBg = $avatarColors[$child->avatar] ?? '#7EC8E3';
        $tierCfg = $child->tierConfig();
        $isPaused = $child->isPaused();
    @endphp

    {{-- Header card --}}
    <div class="bg-white rounded-kid border border-gray-200 p-5 md:p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">

            <div class="flex items-center gap-4 flex-1 min-w-0">
                <div class="w-16 h-16 rounded-full flex items-center justify-center shrink-0"
                     style="background-color: {{ $avatarBg }}">
                    <x-lucide-bot class="w-8 h-8 text-white" stroke-width="2" />
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl md:text-2xl font-extrabold truncate">{{ $child->name }}</h1>
                        @if($isPaused)
                            <span class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-full bg-kid-coral/15 text-kid-coral">
                                <x-lucide-pause class="w-3 h-3" stroke-width="3" />
                                {{ app()->getLocale() === 'id' ? 'Dijeda' : 'Paused' }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 truncate">{{ $child->email }}</p>
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-kid-blue mt-1">
                        <x-dynamic-component :component="'lucide-' . $tierCfg['icon']" class="w-3 h-3" stroke-width="2.5" />
                        {{ $tierCfg['label_' . app()->getLocale()] }}
                        <span class="text-gray-400 font-normal">· {{ $tierCfg['ages'] }}</span>
                    </span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-4 md:flex gap-3 md:gap-4 md:shrink-0">
                <div class="text-center">
                    <div class="text-lg md:text-xl font-extrabold">{{ $stats['stars'] }}</div>
                    <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ app()->getLocale() === 'id' ? 'Bintang' : 'Stars' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg md:text-xl font-extrabold">{{ $stats['finished'] }}</div>
                    <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ app()->getLocale() === 'id' ? 'Selesai' : 'Done' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg md:text-xl font-extrabold">{{ $stats['mastered'] }}</div>
                    <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ app()->getLocale() === 'id' ? 'Sempurna' : 'Perfect' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg md:text-xl font-extrabold">{{ $stats['badges'] }}</div>
                    <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ app()->getLocale() === 'id' ? 'Lencana' : 'Badges' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== EDIT PROFILE ==================== --}}
    <div class="bg-white rounded-kid border border-gray-200 p-5 md:p-6 mb-6">
        <h2 class="text-lg font-extrabold mb-4 flex items-center gap-2">
            <x-lucide-pencil class="w-4 h-4 text-gray-400" stroke-width="2.5" />
            {{ app()->getLocale() === 'id' ? 'Edit Profil' : 'Edit Profile' }}
        </h2>

        @if(session()->has('success_profile'))
            <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm">{{ session('success_profile') }}</div>
        @endif

        <form wire:submit="updateProfile" class="space-y-4">

            <div>
                <label class="block font-semibold mb-1 text-sm">
                    {{ app()->getLocale() === 'id' ? 'Nama' : 'Name' }}
                </label>
                <input type="text" wire:model="name"
                       class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue">
                @error('name') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-2 text-sm">Avatar</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($avatarColors as $key => $color)
                        <button type="button" wire:click="$set('avatar', '{{ $key }}')"
                                class="w-11 h-11 rounded-full border-4 transition
                                       {{ $avatar === $key ? 'border-kid-blue scale-110' : 'border-transparent' }}"
                                style="background-color: {{ $color }}"></button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-2 text-sm">
                    {{ app()->getLocale() === 'id' ? 'Level' : 'Tier' }}
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach(config('tiers') as $key => $t)
                        <button type="button" wire:click="$set('tier', '{{ $key }}')"
                                class="p-3 rounded-kid border-2 text-left transition
                                       {{ $tier === $key ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                            @php $colorMap = ['mint' => 'bg-kid-mint', 'yellow' => 'bg-kid-yellow', 'coral' => 'bg-kid-coral', 'purple' => 'bg-kid-purple']; @endphp
                            <div class="w-8 h-8 rounded-full {{ $colorMap[$t['color']] }} flex items-center justify-center text-white mb-2">
                                <x-dynamic-component :component="'lucide-' . $t['icon']" class="w-4 h-4" stroke-width="2.5" />
                            </div>
                            <div class="font-extrabold text-xs">{{ $t['label_' . app()->getLocale()] }}</div>
                            <div class="text-[10px] text-gray-400">{{ $t['ages'] }}</div>
                        </button>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-kid bg-kid-blue text-white w-full md:w-auto">
                {{ app()->getLocale() === 'id' ? 'Simpan Perubahan' : 'Save Changes' }}
            </button>
        </form>
    </div>

    {{-- ==================== RESET PASSWORD ==================== --}}
    <div class="bg-white rounded-kid border border-gray-200 p-5 md:p-6 mb-6">
        <h2 class="text-lg font-extrabold mb-4 flex items-center gap-2">
            <x-lucide-key-round class="w-4 h-4 text-gray-400" stroke-width="2.5" />
            {{ app()->getLocale() === 'id' ? 'Reset Kata Sandi' : 'Reset Password' }}
        </h2>

        @if(session()->has('success_password'))
            <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm">{{ session('success_password') }}</div>
        @endif

        <form wire:submit="resetPassword" class="space-y-4">
            <div>
                <label class="block font-semibold mb-1 text-sm">
                    {{ app()->getLocale() === 'id' ? 'Kata Sandi Baru' : 'New Password' }}
                </label>
                <input type="text" wire:model="newPassword" placeholder="min. 6"
                       class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue font-mono">
                @error('newPassword') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-kid bg-white border-2 border-gray-200 text-kid-text w-full md:w-auto">
                <x-lucide-refresh-cw class="w-4 h-4 mr-2" stroke-width="2.5" />
                <span>{{ app()->getLocale() === 'id' ? 'Reset Kata Sandi' : 'Reset Password' }}</span>
            </button>
        </form>
    </div>

    {{-- ==================== ACCOUNT STATUS ==================== --}}
    <div class="bg-white rounded-kid border border-gray-200 p-5 md:p-6 mb-6">
        <h2 class="text-lg font-extrabold mb-4 flex items-center gap-2">
            <x-lucide-power class="w-4 h-4 text-gray-400" stroke-width="2.5" />
            {{ app()->getLocale() === 'id' ? 'Status Akun' : 'Account Status' }}
        </h2>

        @if(session()->has('success_status'))
            <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm">{{ session('success_status') }}</div>
        @endif

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $isPaused ? 'bg-kid-coral/15 text-kid-coral' : 'bg-kid-mint/25 text-kid-text' }}">
                    <x-dynamic-component :component="$isPaused ? 'lucide-pause' : 'lucide-play'" class="w-5 h-5" stroke-width="2.5" />
                </div>
                <div>
                    <div class="font-bold text-sm">
                        {{ $isPaused
                            ? (app()->getLocale() === 'id' ? 'Dijeda' : 'Paused')
                            : (app()->getLocale() === 'id' ? 'Aktif' : 'Active') }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $isPaused
                            ? (app()->getLocale() === 'id' ? 'Anak tidak dapat masuk sampai diaktifkan.' : 'Child cannot log in until resumed.')
                            : (app()->getLocale() === 'id' ? 'Anak dapat masuk dan bermain.' : 'Child can log in and play.') }}
                    </div>
                </div>
            </div>

            <button wire:click="togglePause"
                    class="btn-kid {{ $isPaused ? 'bg-kid-mint text-white' : 'bg-white border-2 border-gray-200 text-kid-text' }} text-sm">
                <x-dynamic-component :component="$isPaused ? 'lucide-play' : 'lucide-pause'" class="w-4 h-4 mr-2" stroke-width="2.5" />
                <span>{{ $isPaused
                    ? (app()->getLocale() === 'id' ? 'Aktifkan' : 'Resume')
                    : (app()->getLocale() === 'id' ? 'Jeda' : 'Pause') }}</span>
            </button>
        </div>
    </div>

    {{-- ==================== PROGRESS ==================== --}}
    <div class="bg-white rounded-kid border border-gray-200 p-5 md:p-6 mb-6">
        <h2 class="text-lg font-extrabold mb-4 flex items-center gap-2">
            <x-lucide-trending-up class="w-4 h-4 text-gray-400" stroke-width="2.5" />
            {{ app()->getLocale() === 'id' ? 'Progres Belajar' : 'Learning Progress' }}
        </h2>

        @if($progress->isEmpty())
            <p class="text-sm text-gray-500 text-center py-6">
                {{ app()->getLocale() === 'id' ? 'Anak belum memainkan level apa pun.' : 'Child hasn\'t played any levels yet.' }}
            </p>
        @else
            <div class="space-y-2">
                @foreach($progress as $p)
                    <div class="flex items-center gap-3 p-3 rounded-kid bg-kid-bg">
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-sm truncate">
                                {{ $p->level?->title ?? '—' }}
                            </div>
                            <div class="text-xs text-gray-500 truncate">
                                {{ $p->level?->module?->title ?? '—' }}
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            @for($s = 1; $s <= 3; $s++)
                                <x-lucide-star class="w-4 h-4 {{ $s <= $p->stars ? 'text-kid-yellow fill-current' : 'text-gray-200' }}" stroke-width="1.5" />
                            @endfor
                        </div>
                        <div class="text-xs text-gray-400 shrink-0 tabular-nums hidden md:block">
                            {{ $p->attempts }}x
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ==================== DANGER ZONE ==================== --}}
    <div class="bg-white rounded-kid border-2 border-kid-coral/30 p-5 md:p-6">
        <h2 class="text-lg font-extrabold mb-2 flex items-center gap-2 text-kid-coral">
            <x-lucide-alert-triangle class="w-4 h-4" stroke-width="2.5" />
            {{ app()->getLocale() === 'id' ? 'Zona Berbahaya' : 'Danger Zone' }}
        </h2>
        <p class="text-sm text-gray-500 mb-4">
            {{ app()->getLocale() === 'id'
                ? 'Menghapus akun anak akan menghapus semua progres dan lencana mereka secara permanen.'
                : 'Deleting the child account permanently removes all their progress and badges.' }}
        </p>
        <button wire:click="confirmDelete"
                class="btn-kid bg-white border-2 border-kid-coral text-kid-coral text-sm">
            <x-lucide-trash-2 class="w-4 h-4 mr-2" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Hapus Akun Anak' : 'Delete Child Account' }}</span>
        </button>
    </div>

    {{-- ==================== DELETE MODAL ==================== --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             wire:click.self="cancelDelete">
            <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-6 md:p-8 max-w-md w-full text-center">

                <div class="w-16 h-16 rounded-full bg-kid-coral/15 flex items-center justify-center mx-auto mb-4 animate-pulse-soft">
                    <x-lucide-alert-triangle class="w-8 h-8 text-kid-coral" stroke-width="2.5" />
                </div>

                <h2 class="text-xl md:text-2xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Hapus Akun Anak?' : 'Delete Child Account?' }}
                </h2>

                <div class="bg-kid-bg rounded-kid p-3 my-4">
                    <div class="font-bold text-sm">{{ $child->name }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ $child->email }}</div>
                </div>

                <div class="bg-kid-coral/10 rounded-kid p-3 text-xs text-left space-y-1">
                    <div class="font-bold text-kid-coral mb-1">
                        {{ app()->getLocale() === 'id' ? '⚠ Peringatan' : '⚠ Warning' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <x-lucide-trending-up class="w-3 h-3 text-kid-coral shrink-0" stroke-width="2.5" />
                        {{ $stats['played'] }} {{ app()->getLocale() === 'id' ? 'catatan progres akan hilang.' : 'progress record(s) will be lost.' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <x-lucide-award class="w-3 h-3 text-kid-coral shrink-0" stroke-width="2.5" />
                        {{ $stats['badges'] }} {{ app()->getLocale() === 'id' ? 'lencana akan hilang.' : 'badge(s) will be lost.' }}
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="cancelDelete"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-gray-100 text-gray-700 text-sm">
                        {{ app()->getLocale() === 'id' ? 'Batal' : 'Cancel' }}
                    </button>
                    <button wire:click="delete"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-kid-coral text-white text-sm">
                        {{ app()->getLocale() === 'id' ? 'Ya, Hapus' : 'Yes, Delete' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>