<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold">
                {{ app()->getLocale() === 'id' ? 'Dasbor Admin' : 'Admin Dashboard' }}
            </h1>
            <p class="text-gray-500 mt-1 text-sm md:text-base">
                {{ app()->getLocale() === 'id' ? 'Kelola pengguna dan konten.' : 'Manage users and content.' }}
            </p>
        </div>

    <div class="flex flex-wrap gap-2 w-full md:w-auto">
    <a href="{{ route('admin.modules') }}"
       class="btn-kid bg-white border-2 border-gray-200 text-kid-text gap-2 flex-1 md:flex-none justify-center">
        <x-lucide-layers class="w-5 h-5" stroke-width="2.5" />
        <span>{{ app()->getLocale() === 'id' ? 'Kelola Modul' : 'Manage Modules' }}</span>
    </a>
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <a href="{{ route('admin.modules') }}" class="...">...</a>
            <a href="{{ route('admin.levels') }}" class="...">...</a>
            <button wire:click="openCreate" class="btn-kid bg-kid-blue text-white gap-2 flex-1 md:flex-none justify-center">
                <x-lucide-user-plus class="w-5 h-5" stroke-width="2.5" />
                <span>{{ app()->getLocale() === 'id' ? 'Tambah Pengguna' : 'Add User' }}</span>
            </button>
        </div>
    <a href="{{ route('admin.levels') }}"
       class="btn-kid bg-white border-2 border-gray-200 text-kid-text gap-2 flex-1 md:flex-none justify-center">
        <x-lucide-gamepad-2 class="w-5 h-5" stroke-width="2.5" />
        <span>{{ app()->getLocale() === 'id' ? 'Kelola Level' : 'Manage Levels' }}</span>
    </a>
</div>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
            {{ session('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mb-4 bg-kid-coral/20 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
            <x-lucide-alert-circle class="w-4 h-4 text-kid-coral" stroke-width="2.5" />
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3 md:gap-6 mb-8">
        <div class="bg-white rounded-kid border border-gray-200 p-4 md:p-6 text-center">
            <x-lucide-users class="w-5 h-5 md:w-6 md:h-6 mx-auto text-kid-blue mb-2" stroke-width="2.5" />
            <div class="text-2xl md:text-4xl font-extrabold text-kid-blue">{{ $totalParents }}</div>
            <div class="text-xs md:text-sm text-gray-500">{{ app()->getLocale() === 'id' ? 'Orang Tua' : 'Parents' }}</div>
        </div>
        <div class="bg-white rounded-kid border border-gray-200 p-4 md:p-6 text-center">
            <x-lucide-graduation-cap class="w-5 h-5 md:w-6 md:h-6 mx-auto text-kid-coral mb-2" stroke-width="2.5" />
            <div class="text-2xl md:text-4xl font-extrabold text-kid-coral">{{ $totalStudents }}</div>
            <div class="text-xs md:text-sm text-gray-500">{{ app()->getLocale() === 'id' ? 'Siswa' : 'Students' }}</div>
        </div>
        <div class="bg-white rounded-kid border border-gray-200 p-4 md:p-6 text-center">
            <x-lucide-user-cog class="w-5 h-5 md:w-6 md:h-6 mx-auto text-kid-purple mb-2" stroke-width="2.5" />
            <div class="text-2xl md:text-4xl font-extrabold text-kid-purple">{{ $users->count() }}</div>
            <div class="text-xs md:text-sm text-gray-500">{{ app()->getLocale() === 'id' ? 'Total' : 'Total' }}</div>
        </div>
    </div>

    {{-- Users table --}}
    <div class="bg-white rounded-kid border border-gray-200 overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100">
            <h2 class="font-extrabold">
                {{ app()->getLocale() === 'id' ? 'Semua Pengguna' : 'All Users' }}
            </h2>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($users as $user)
                @php
                    $avatarColors = [
                        'robot-blue'   => '#7EC8E3',
                        'robot-green'  => '#A8E6CF',
                        'robot-yellow' => '#FFD97D',
                        'robot-coral'  => '#FF8B94',
                        'robot-purple' => '#B39DDB',
                        'robot-mint'   => '#B8E0D2',
                    ];
                    $avatarBg = $avatarColors[$user->avatar] ?? '#7EC8E3';
                    $isSelf = $user->id === auth()->id();
                @endphp
                <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 hover:bg-kid-bg/40 transition">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center shrink-0"
                         style="background-color: {{ $avatarBg }}">
                        <x-lucide-bot class="w-5 h-5 md:w-6 md:h-6 text-white" stroke-width="2" />
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-sm md:text-base truncate">{{ $user->name }}</span>
                            @if($isSelf)
                                <span class="text-[10px] uppercase tracking-wider font-extrabold text-gray-400">
                                    {{ app()->getLocale() === 'id' ? 'Anda' : 'You' }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs md:text-sm text-gray-500 truncate">{{ $user->email }}</div>
                    </div>

                    {{-- Role pill --}}
                    <span class="hidden md:inline-flex text-[10px] uppercase tracking-wider font-extrabold px-2.5 py-1 rounded-full shrink-0
                                 {{ $user->isAdmin() ? 'bg-kid-purple/15 text-kid-purple' : ($user->isParent() ? 'bg-kid-blue/15 text-kid-blue' : 'bg-kid-mint/25 text-kid-text') }}">
                        {{ $user->role }}
                    </span>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 shrink-0">
                        <button wire:click="openView({{ $user->id }})"
                                class="p-2 rounded-full hover:bg-kid-bg text-gray-500 hover:text-kid-blue transition"
                                title="{{ app()->getLocale() === 'id' ? 'Lihat' : 'View' }}">
                            <x-lucide-eye class="w-4 h-4" stroke-width="2.5" />
                        </button>
                        <button wire:click="openEdit({{ $user->id }})"
                                class="p-2 rounded-full hover:bg-kid-bg text-gray-500 hover:text-kid-blue transition"
                                title="{{ app()->getLocale() === 'id' ? 'Edit' : 'Edit' }}">
                            <x-lucide-pencil class="w-4 h-4" stroke-width="2.5" />
                        </button>
                        @if(! $isSelf)
                            <button wire:click="confirmDelete({{ $user->id }})"
                                    class="p-2 rounded-full hover:bg-kid-coral/10 text-gray-500 hover:text-kid-coral transition"
                                    title="{{ app()->getLocale() === 'id' ? 'Hapus' : 'Delete' }}">
                                <x-lucide-trash-2 class="w-4 h-4" stroke-width="2.5" />
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ==================== EDIT MODAL ==================== --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-0 md:p-4"
             wire:click.self="closeEdit">
            <div class="animate-pop-in bg-white rounded-t-kid md:rounded-kid shadow-2xl w-full md:max-w-lg
                        max-h-[92vh] md:max-h-[90vh] flex flex-col">

                <div class="flex justify-between items-center p-4 md:p-6 border-b border-gray-100 shrink-0">
                    <h2 class="text-lg md:text-xl font-extrabold">
                        {{ app()->getLocale() === 'id' ? 'Edit Pengguna' : 'Edit User' }}
                    </h2>
                    <button wire:click="closeEdit" class="text-gray-400 hover:text-gray-700 p-1">
                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                    </button>
                </div>

                <form wire:submit="saveEdit" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">

                    <div>
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Nama' : 'Name' }}
                        </label>
                        <input type="text" wire:model="name"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                        @error('name') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Email</label>
                        <input type="email" wire:model="email"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                        @error('email') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-2 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Avatar' : 'Avatar' }}
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $avatarColors = [
                                    'robot-blue'   => '#7EC8E3',
                                    'robot-green'  => '#A8E6CF',
                                    'robot-yellow' => '#FFD97D',
                                    'robot-coral'  => '#FF8B94',
                                    'robot-purple' => '#B39DDB',
                                    'robot-mint'   => '#B8E0D2',
                                ];
                            @endphp
                            @foreach($avatarColors as $key => $color)
                                <button type="button" wire:click="$set('avatar', '{{ $key }}')"
                                        class="w-10 h-10 rounded-full border-2 md:border-4 transition
                                               {{ $avatar === $key ? 'border-kid-blue scale-110' : 'border-transparent' }}"
                                        style="background-color: {{ $color }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Peran' : 'Role' }}
                        </label>
                   <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
    @foreach(['admin', 'parent', 'student', 'teacher'] as $r)
        <button type="button" wire:click="$set('role', '{{ $r }}')"
                class="p-2 rounded-kid border-2 text-xs font-bold capitalize transition
                       {{ $role === $r ? 'border-kid-blue bg-kid-blue/5 text-kid-blue' : 'border-gray-200 text-gray-500' }}">
            {{ $r }}
        </button>
    @endforeach
</div>
                        @error('role') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($role === 'student')
                        <div>
                            <label class="block font-semibold mb-2 text-sm">
                                {{ app()->getLocale() === 'id' ? 'Level' : 'Tier' }}
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(config('tiers') as $key => $t)
                                    <button type="button" wire:click="$set('tier', '{{ $key }}')"
                                            class="flex items-center gap-2 p-2 rounded-kid border-2 text-left transition
                                                   {{ $tier === $key ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                                        @php
                                            $colorMap = [
                                                'mint' => 'bg-kid-mint', 'yellow' => 'bg-kid-yellow',
                                                'coral' => 'bg-kid-coral', 'purple' => 'bg-kid-purple',
                                            ];
                                        @endphp
                                        <div class="w-7 h-7 rounded-full {{ $colorMap[$t['color']] }} flex items-center justify-center text-white shrink-0">
                                            <x-dynamic-component :component="'lucide-' . $t['icon']" class="w-3.5 h-3.5" stroke-width="2.5" />
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-xs truncate">{{ $t['label_' . app()->getLocale()] }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $t['ages'] }}</div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                            @error('tier') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Kata Sandi Baru' : 'New Password' }}
                            <span class="text-gray-400 font-normal text-xs">
                                ({{ app()->getLocale() === 'id' ? 'opsional' : 'optional' }})
                            </span>
                        </label>
                        <input type="text" wire:model="newPassword"
                               placeholder="{{ app()->getLocale() === 'id' ? 'Kosongkan jika tidak diubah' : 'Leave blank to keep current' }}"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2 font-mono">
                        @error('newPassword') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                </form>

                <div class="p-4 md:p-6 border-t border-gray-100 flex gap-3 shrink-0">
                    <button type="button" wire:click="closeEdit"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-gray-100 text-gray-700 text-sm">
                        {{ app()->getLocale() === 'id' ? 'Batal' : 'Cancel' }}
                    </button>
                    <button type="button" wire:click="saveEdit"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-kid-blue text-white text-sm">
                        {{ app()->getLocale() === 'id' ? 'Simpan' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== VIEW MODAL ==================== --}}
    @if($showViewModal && $viewingUser)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-0 md:p-4"
             wire:click.self="closeView">
            <div class="animate-pop-in bg-white rounded-t-kid md:rounded-kid shadow-2xl w-full md:max-w-2xl
                        max-h-[92vh] md:max-h-[90vh] flex flex-col">

                <div class="flex justify-between items-center p-4 md:p-6 border-b border-gray-100 shrink-0">
                    <h2 class="text-lg md:text-xl font-extrabold">
                        {{ app()->getLocale() === 'id' ? 'Detail Pengguna' : 'User Detail' }}
                    </h2>
                    <button wire:click="closeView" class="text-gray-400 hover:text-gray-700 p-1">
                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6">

                    @php
                        $avatarColors = [
                            'robot-blue'   => '#7EC8E3', 'robot-green'  => '#A8E6CF',
                            'robot-yellow' => '#FFD97D', 'robot-coral'  => '#FF8B94',
                            'robot-purple' => '#B39DDB', 'robot-mint'   => '#B8E0D2',
                        ];
                        $avatarBg = $avatarColors[$viewingUser->avatar] ?? '#7EC8E3';
                        $tierCfg = $viewingUser->isStudent() ? $viewingUser->tierConfig() : null;
                    @endphp

                    {{-- Header --}}
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: {{ $avatarBg }}">
                            <x-lucide-bot class="w-8 h-8 text-white" stroke-width="2" />
                        </div>
                        <div class="min-w-0">
                            <div class="font-extrabold text-lg truncate">{{ $viewingUser->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $viewingUser->email }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-full
                                             {{ $viewingUser->isAdmin() ? 'bg-kid-purple/15 text-kid-purple' : ($viewingUser->isParent() ? 'bg-kid-blue/15 text-kid-blue' : 'bg-kid-mint/25 text-kid-text') }}">
                                    {{ $viewingUser->role }}
                                </span>
                                @if($tierCfg)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-kid-blue">
                                        <x-dynamic-component :component="'lucide-' . $tierCfg['icon']" class="w-3 h-3" stroke-width="2.5" />
                                        {{ $tierCfg['label_' . app()->getLocale()] }}
                                        <span class="text-gray-400 font-normal">· {{ $tierCfg['ages'] }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ==================== STUDENT VIEW ==================== --}}
                    @if($viewingUser->isStudent() && $viewingStats)
                        <div>
                            <h3 class="text-xs uppercase tracking-wider font-extrabold text-gray-400 mb-3">
                                {{ app()->getLocale() === 'id' ? 'Statistik' : 'Stats' }}
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="bg-kid-bg rounded-kid p-4 text-center">
                                    <x-lucide-gamepad-2 class="w-5 h-5 mx-auto text-kid-blue mb-1" stroke-width="2.5" />
                                    <div class="text-2xl font-extrabold">{{ $viewingStats['played'] }}</div>
                                    <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                                        {{ app()->getLocale() === 'id' ? 'Dimainkan' : 'Played' }}
                                    </div>
                                </div>
                                <div class="bg-kid-bg rounded-kid p-4 text-center">
                                    <x-lucide-flag class="w-5 h-5 mx-auto text-kid-coral mb-1" stroke-width="2.5" />
                                    <div class="text-2xl font-extrabold">{{ $viewingStats['finished'] }}</div>
                                    <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                                        {{ app()->getLocale() === 'id' ? 'Selesai' : 'Finished' }}
                                    </div>
                                </div>
                                <div class="bg-kid-bg rounded-kid p-4 text-center">
                                    <x-lucide-star class="w-5 h-5 mx-auto text-kid-yellow fill-current mb-1" stroke-width="1.5" />
                                    <div class="text-2xl font-extrabold">{{ $viewingStats['stars'] }}</div>
                                    <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                                        {{ app()->getLocale() === 'id' ? 'Bintang' : 'Stars' }}
                                    </div>
                                </div>
                                <div class="bg-kid-bg rounded-kid p-4 text-center">
                                    <x-lucide-trophy class="w-5 h-5 mx-auto text-kid-purple mb-1" stroke-width="2.5" />
                                    <div class="text-2xl font-extrabold">{{ $viewingStats['mastered'] }}</div>
                                    <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                                        {{ app()->getLocale() === 'id' ? 'Sempurna' : 'Mastered' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center gap-2 p-3 rounded-kid bg-kid-bg">
                                <x-lucide-repeat class="w-4 h-4 text-gray-400 shrink-0" stroke-width="2.5" />
                                <span class="text-gray-500">
                                    {{ app()->getLocale() === 'id' ? 'Total percobaan:' : 'Total attempts:' }}
                                </span>
                                <strong class="ml-auto">{{ $viewingStats['attempts'] }}</strong>
                            </div>
                            <div class="flex items-center gap-2 p-3 rounded-kid bg-kid-bg">
                                <x-lucide-award class="w-4 h-4 text-gray-400 shrink-0" stroke-width="2.5" />
                                <span class="text-gray-500">
                                    {{ app()->getLocale() === 'id' ? 'Lencana:' : 'Badges:' }}
                                </span>
                                <strong class="ml-auto">{{ $viewingStats['badges'] }}</strong>
                            </div>
                        </div>
                    @endif

                    {{-- ==================== PARENT VIEW ==================== --}}
                    @if($viewingUser->isParent())
                        <div>
                            <h3 class="text-xs uppercase tracking-wider font-extrabold text-gray-400 mb-3">
                                {{ app()->getLocale() === 'id' ? 'Anak-Anak' : 'Children' }}
                                <span class="text-gray-300">·</span>
                                {{ $viewingChildren->count() }}
                            </h3>

                            @if($viewingChildren->isEmpty())
                                <div class="bg-kid-bg rounded-kid p-6 text-center text-sm text-gray-500">
                                    {{ app()->getLocale() === 'id' ? 'Belum ada anak.' : 'No children yet.' }}
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($viewingChildren as $child)
                                        @php
                                            $childBg = $avatarColors[$child->avatar] ?? '#7EC8E3';
                                            $childTier = $child->tierConfig();
                                        @endphp
                                        <div class="flex items-center gap-3 p-3 rounded-kid bg-kid-bg">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                                                 style="background-color: {{ $childBg }}">
                                                <x-lucide-bot class="w-5 h-5 text-white" stroke-width="2" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-bold text-sm truncate">{{ $child->name }}</div>
                                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                                    <x-dynamic-component :component="'lucide-' . $childTier['icon']" class="w-3 h-3" stroke-width="2.5" />
                                                    {{ $childTier['label_' . app()->getLocale()] }}
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 text-xs shrink-0">
                                                <span class="flex items-center gap-1 font-bold">
                                                    {{ $child->stars }}
                                                    <x-lucide-star class="w-3 h-3 text-kid-yellow fill-current" stroke-width="1.5" />
                                                </span>
                                                <span class="flex items-center gap-1 font-bold">
                                                    {{ $child->finished }}
                                                    <x-lucide-flag class="w-3 h-3 text-kid-coral" stroke-width="2.5" />
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <div class="p-4 md:p-6 border-t border-gray-100 shrink-0">
                    <button type="button" wire:click="closeView"
                            class="w-full rounded-kid px-4 py-2.5 font-bold bg-gray-100 text-gray-700 text-sm">
                        {{ app()->getLocale() === 'id' ? 'Tutup' : 'Close' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== DELETE MODAL ==================== --}}
    @if($showDeleteModal && $deletingId)
        @php
            $deleting = \App\Models\User::withCount(['children', 'progress'])->find($deletingId);
        @endphp
        @if($deleting)
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                 wire:click.self="cancelDelete">
                <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-6 md:p-8 max-w-md w-full text-center">

                    <div class="w-16 h-16 rounded-full bg-kid-coral/15 flex items-center justify-center mx-auto mb-4 animate-pulse-soft">
                        <x-lucide-alert-triangle class="w-8 h-8 text-kid-coral" stroke-width="2.5" />
                    </div>

                    <h2 class="text-xl md:text-2xl font-extrabold mb-2">
                        {{ app()->getLocale() === 'id' ? 'Hapus Pengguna?' : 'Delete User?' }}
                    </h2>

                    <div class="bg-kid-bg rounded-kid p-3 my-4">
                        <div class="font-bold text-sm">{{ $deleting->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $deleting->email }}</div>
                        <div class="text-[10px] uppercase tracking-wider font-extrabold text-gray-400 mt-1">
                            {{ $deleting->role }}
                        </div>
                    </div>

                    @if($deleting->children_count > 0 || $deleting->progress_count > 0)
                        <div class="bg-kid-coral/10 rounded-kid p-3 text-xs text-kid-text text-left space-y-1">
                            <div class="font-bold text-kid-coral mb-1">
                                {{ app()->getLocale() === 'id' ? '⚠ Peringatan' : '⚠ Warning' }}
                            </div>
                            @if($deleting->children_count > 0)
                                <div class="flex items-center gap-2">
                                    <x-lucide-users class="w-3 h-3 text-kid-coral shrink-0" stroke-width="2.5" />
                                    {{ app()->getLocale() === 'id'
                                        ? "{$deleting->children_count} akun anak juga akan dihapus."
                                        : "{$deleting->children_count} child account(s) will also be deleted." }}
                                </div>
                            @endif
                            @if($deleting->progress_count > 0)
                                <div class="flex items-center gap-2">
                                    <x-lucide-trending-up class="w-3 h-3 text-kid-coral shrink-0" stroke-width="2.5" />
                                    {{ app()->getLocale() === 'id'
                                        ? "{$deleting->progress_count} catatan progres akan hilang."
                                        : "{$deleting->progress_count} progress record(s) will be lost." }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            {{ app()->getLocale() === 'id'
                                ? 'Tindakan ini tidak dapat dibatalkan.'
                                : 'This action cannot be undone.' }}
                        </p>
                    @endif

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
    @endif

    {{-- ==================== CREATE USER MODAL ==================== --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-0 md:p-4"
             wire:click.self="closeCreate">
            <div class="animate-pop-in bg-white rounded-t-kid md:rounded-kid shadow-2xl w-full md:max-w-lg
                        max-h-[92vh] md:max-h-[90vh] flex flex-col">

                <div class="flex justify-between items-center p-4 md:p-6 border-b border-gray-100 shrink-0">
                    <h2 class="text-lg md:text-xl font-extrabold">
                        {{ app()->getLocale() === 'id' ? 'Tambah Pengguna Baru' : 'Add New User' }}
                    </h2>
                    <button wire:click="closeCreate" class="text-gray-400 hover:text-gray-700 p-1">
                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                    </button>
                </div>

                <form wire:submit="saveCreate" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Name</label>
                        <input type="text" wire:model="newName" class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                        @error('newName') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Email</label>
                        <input type="email" wire:model="newEmail" class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                        @error('newEmail') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Password</label>
                        <input type="text" wire:model="newPassword" placeholder="min. 8" class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2 font-mono">
                        @error('newPassword') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-2 text-sm">Role</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach(['admin', 'parent', 'student', 'teacher'] as $r)
                                <button type="button" wire:click="$set('newRole', '{{ $r }}')"
                                        class="p-2 rounded-kid border-2 text-xs font-bold capitalize transition
                                               {{ $newRole === $r ? 'border-kid-blue bg-kid-blue/5 text-kid-blue' : 'border-gray-200 text-gray-500' }}">
                                    {{ $r }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if($newRole === 'student')
                        <div>
                            <label class="block font-semibold mb-2 text-sm">Tier</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(config('tiers') as $key => $t)
                                    <button type="button" wire:click="$set('newTier', '{{ $key }}')"
                                            class="p-2 rounded-kid border-2 text-left transition
                                                   {{ $newTier === $key ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                                        <div class="font-bold text-xs truncate">{{ $t['label_' . app()->getLocale()] }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block font-semibold mb-2 text-sm">Avatar</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $colors = [
                                    'robot-blue'   => '#7EC8E3', 'robot-green'  => '#A8E6CF',
                                    'robot-yellow' => '#FFD97D', 'robot-coral'  => '#FF8B94',
                                    'robot-purple' => '#B39DDB', 'robot-mint'   => '#B8E0D2',
                                ];
                            @endphp
                            @foreach($colors as $key => $color)
                                <button type="button" wire:click="$set('newAvatar', '{{ $key }}')"
                                        class="w-10 h-10 rounded-full border-2 md:border-4 transition
                                               {{ $newAvatar === $key ? 'border-kid-blue scale-110' : 'border-transparent' }}"
                                        style="background-color: {{ $color }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                </form>

                <div class="p-4 md:p-6 border-t border-gray-100 flex gap-3 shrink-0">
                    <button type="button" wire:click="closeCreate"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-gray-100 text-gray-700 text-sm">
                        Batal
                    </button>
                    <button type="button" wire:click="saveCreate"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-kid-blue text-white text-sm">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>