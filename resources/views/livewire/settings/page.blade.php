

<div class="max-w-3xl mx-auto"
     x-data="{ tab: 'profile' }">

     <style>
    [x-cloak] { display: none !important; }
</style>

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
            <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
            <span>{{ __('ui.dashboard') }}</span>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold mt-2">
            {{ app()->getLocale() === 'id' ? 'Pengaturan' : 'Settings' }}
        </h1>
    </div>

    {{-- Card with tabs --}}
    <div class="bg-white rounded-kid border border-gray-200 overflow-hidden">

        {{-- Tabs --}}
        <nav class="grid grid-cols-2 md:flex border-b border-gray-200">
            @php
                $tabs = [
                    ['key' => 'profile', 'icon' => 'user', 'en' => 'Profile', 'id' => 'Profil'],
                    ['key' => 'prefs',   'icon' => 'sliders-horizontal', 'en' => 'Preferences', 'id' => 'Preferensi'],
                    ['key' => 'account', 'icon' => 'id-card', 'en' => 'Account', 'id' => 'Akun'],
                ];
                if (! auth()->user()->isStudent()) {
                    $tabs[] = ['key' => 'security', 'icon' => 'lock', 'en' => 'Security', 'id' => 'Keamanan'];
                }
                $tabs[] = ['key' => 'about', 'icon' => 'info', 'en' => 'About', 'id' => 'Tentang'];
            @endphp

            @foreach($tabs as $t)
                <button type="button" @click="tab = '{{ $t['key'] }}'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 font-bold text-sm transition
                               border-b-2 md:border-b-0 md:border-r md:last:border-r-0 border-gray-100"
                        :class="tab === '{{ $t['key'] }}'
                            ? 'bg-kid-blue/5 text-kid-blue border-b-kid-blue md:border-b-transparent md:border-r-gray-100'
                            : 'text-gray-500 hover:bg-gray-50'">
                    <x-dynamic-component :component="'lucide-' . $t['icon']" class="w-4 h-4" stroke-width="2.5" />
                    <span>{{ app()->getLocale() === 'id' ? $t['id'] : $t['en'] }}</span>
                </button>
            @endforeach
        </nav>

        <div class="p-6 md:p-8">

               {{-- ==================== ABOUT ==================== --}}
<div x-show="tab === 'about'" x-cloak>
    <h2 class="text-lg font-extrabold mb-4">
        {{ app()->getLocale() === 'id' ? 'Tentang AlgoritmaKids' : 'About AlgoritmaKids' }}
    </h2>

    {{-- Logo + tagline --}}
    <div class="text-center py-6 mb-6">
        <div class="text-3xl font-extrabold mb-2">
            <span class="text-kid-blue">Algoritma</span><span class="text-kid-mint">Kids</span>
        </div>
        <p class="text-gray-500 text-sm max-w-md mx-auto">
            {{ app()->getLocale() === 'id'
                ? 'Belajar logika coding melalui permainan yang menyenangkan, aman, dan gratis.'
                : 'Learn coding logic through fun, safe, and free games.' }}
        </p>
        <div class="text-xs text-gray-400 mt-3">v1.0 · {{ now()->format('M Y') }}</div>
    </div>

    {{-- Info grid --}}
    <div class="space-y-3">

        <div class="flex items-start gap-3 p-4 rounded-kid bg-kid-bg">
            <div class="w-9 h-9 rounded-full bg-kid-blue flex items-center justify-center text-white shrink-0">
                <x-lucide-graduation-cap class="w-4 h-4" stroke-width="2.5" />
            </div>
            <div>
                <div class="font-bold text-sm">
                    {{ app()->getLocale() === 'id' ? 'Apa yang diajarkan?' : 'What do we teach?' }}
                </div>
                <div class="text-sm text-gray-500 mt-0.5">
                    {{ app()->getLocale() === 'id'
                        ? 'Urutan, pengulangan, dan kondisi — dasar berpikir komputasional.'
                        : 'Sequencing, loops, and conditionals — the basics of computational thinking.' }}
                </div>
            </div>
        </div>

        <div class="flex items-start gap-3 p-4 rounded-kid bg-kid-bg">
            <div class="w-9 h-9 rounded-full bg-kid-mint flex items-center justify-center text-white shrink-0">
                <x-lucide-shield-check class="w-4 h-4" stroke-width="2.5" />
            </div>
            <div>
                <div class="font-bold text-sm">
                    {{ app()->getLocale() === 'id' ? 'Aman & Bebas Iklan' : 'Safe & Ad-Free' }}
                </div>
                <div class="text-sm text-gray-500 mt-0.5">
                    {{ app()->getLocale() === 'id'
                        ? 'Tanpa iklan, tanpa chat, tanpa pelacakan. Hanya belajar.'
                        : 'No ads, no chat, no tracking. Just learning.' }}
                </div>
            </div>
        </div>

        <div class="flex items-start gap-3 p-4 rounded-kid bg-kid-bg">
            <div class="w-9 h-9 rounded-full bg-kid-yellow flex items-center justify-center text-kid-text shrink-0">
                <x-lucide-code class="w-4 h-4" stroke-width="2.5" />
            </div>
            <div>
                <div class="font-bold text-sm">
                    {{ app()->getLocale() === 'id' ? 'Dibuat oleh' : 'Built by' }}
                </div>
                <div class="text-sm text-gray-500 mt-0.5">
                    {{ app()->getLocale() === 'id'
                        ? 'Sebuah proyek mahasiswa, terinspirasi dari AlgoritmaKids.com'
                        : 'A student project, inspired by AlgoritmaKids.com' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Links --}}
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('privacy') }}"
           class="flex-1 btn-kid bg-white border-2 border-gray-200 text-kid-text text-sm gap-2">
            <x-lucide-file-text class="w-4 h-4" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}</span>
        </a>
        <a href="mailto:hello@algoritmakids.test"
           class="flex-1 btn-kid bg-white border-2 border-gray-200 text-kid-text text-sm gap-2">
            <x-lucide-mail class="w-4 h-4" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Hubungi Kami' : 'Contact Us' }}</span>
        </a>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
        © {{ date('Y') }} AlgoritmaKids. {{ __('ui.made_with_love') }}
    </p>
</div>

            {{-- ==================== PROFILE ==================== --}}
            <div x-show="tab === 'profile'" x-cloak>
                <h2 class="text-lg font-extrabold mb-4">
                    {{ app()->getLocale() === 'id' ? 'Profil' : 'Profile' }}
                </h2>

                @if(session()->has('success_profile'))
                    <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
                        <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
                        {{ session('success_profile') }}
                    </div>
                @endif

                <form wire:submit="updateProfile" class="space-y-5">

                    {{-- Preview --}}
                    <div class="flex items-center gap-4 p-4 rounded-kid bg-kid-bg">
                        @php
                            $colorMap = [
                                'robot-blue'   => '#7EC8E3',
                                'robot-green'  => '#A8E6CF',
                                'robot-yellow' => '#FFD97D',
                                'robot-coral'  => '#FF8B94',
                                'robot-purple' => '#B39DDB',
                                'robot-mint'   => '#B8E0D2',
                            ];
                        @endphp
                        <div class="w-16 h-16 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: {{ $colorMap[$avatar] ?? '#7EC8E3' }}">
                            <x-lucide-bot class="w-8 h-8 text-white" stroke-width="2" />
                        </div>
                        <div>
                            <div class="font-extrabold">{{ $name }}</div>
                            <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    {{-- Name (parents/admins only) --}}
                  <div>
    <label class="block font-semibold mb-1 text-sm">
        {{ app()->getLocale() === 'id' ? 'Nama' : 'Name' }}
    </label>
    <input type="text" wire:model="name"
           class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue">
    @error('name') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
</div>

                    {{-- Avatar --}}
                    <div>
                        <label class="block font-semibold mb-2 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Avatar' : 'Avatar' }}
                        </label>
                        <div class="flex flex-wrap gap-3">
                            @foreach(['robot-blue','robot-green','robot-yellow','robot-coral','robot-purple','robot-mint'] as $a)
                                <button type="button" wire:click="$set('avatar', '{{ $a }}')"
                                        class="w-12 h-12 rounded-full border-4 transition
                                               {{ $avatar === $a ? 'border-kid-blue scale-110' : 'border-transparent' }}"
                                        style="background-color: {{ $colorMap[$a] }}">
                                </button>
                            @endforeach
                        </div>
                        @error('avatar') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-kid bg-kid-blue text-white w-full md:w-auto">
                        {{ app()->getLocale() === 'id' ? 'Simpan Profil' : 'Save Profile' }}
                    </button>

                </form>
            </div>

            {{-- ==================== PREFERENCES ==================== --}}
            <div x-show="tab === 'prefs'" x-cloak>
                <h2 class="text-lg font-extrabold mb-4">
                    {{ app()->getLocale() === 'id' ? 'Preferensi' : 'Preferences' }}
                </h2>

                @if(session()->has('success_prefs'))
                    <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
                        <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
                        {{ session('success_prefs') }}
                    </div>
                @endif

                <form wire:submit="updatePreferences" class="space-y-6">

                    {{-- Language --}}
                    <div>
                        <label class="block font-semibold mb-2 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Bahasa' : 'Language' }}
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('language', 'id')"
                                    class="p-4 rounded-kid border-2 transition text-center
                                           {{ $language === 'id' ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                                <div class="font-extrabold">🇮🇩 Bahasa Indonesia</div>
                            </button>
                            <button type="button" wire:click="$set('language', 'en')"
                                    class="p-4 rounded-kid border-2 transition text-center
                                           {{ $language === 'en' ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                                <div class="font-extrabold">🇬🇧 English</div>
                            </button>
                        </div>
                    </div>

                    {{-- Sound toggle --}}
                    <div class="flex items-center justify-between p-4 rounded-kid bg-kid-bg">
                        <div>
                            <div class="font-bold flex items-center gap-2">
                                <x-lucide-volume-2 class="w-4 h-4" stroke-width="2.5" />
                                {{ app()->getLocale() === 'id' ? 'Suara' : 'Sound' }}
                            </div>
                            <div class="text-sm text-gray-500 mt-0.5">
                                {{ app()->getLocale() === 'id' ? 'Suara efek saat bermain' : 'Sound effects during play' }}
                            </div>
                        </div>
                        <button type="button" wire:click="$toggle('soundEnabled')"
                                class="relative inline-flex h-7 w-12 rounded-full transition-colors shrink-0
                                       {{ $soundEnabled ? 'bg-kid-blue' : 'bg-gray-300' }}">
                            <span class="inline-block h-6 w-6 rounded-full bg-white shadow-md transition-transform my-0.5
                                         {{ $soundEnabled ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                        </button>
                    </div>

                    <button type="submit" class="btn-kid bg-kid-blue text-white w-full md:w-auto">
                        {{ app()->getLocale() === 'id' ? 'Simpan Preferensi' : 'Save Preferences' }}
                    </button>

                </form>
            </div>

            {{-- ==================== ACCOUNT ==================== --}}
            <div x-show="tab === 'account'" x-cloak>
                <h2 class="text-lg font-extrabold mb-4">
                    {{ app()->getLocale() === 'id' ? 'Info Akun' : 'Account Info' }}
                </h2>

                <div class="space-y-4">
                    <div class="flex items-start justify-between p-4 rounded-kid bg-kid-bg">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Email</div>
                            <div class="font-semibold">{{ auth()->user()->email }}</div>
                        </div>
                        <x-lucide-mail class="w-5 h-5 text-gray-400" stroke-width="2" />
                    </div>

                    <div class="flex items-start justify-between p-4 rounded-kid bg-kid-bg">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">
                                {{ app()->getLocale() === 'id' ? 'Peran' : 'Role' }}
                            </div>
                            <div class="font-semibold capitalize">{{ auth()->user()->role }}</div>
                        </div>
                        <x-lucide-shield class="w-5 h-5 text-gray-400" stroke-width="2" />
                    </div>

                    @if(auth()->user()->isStudent())
                        @php $tierCfg = auth()->user()->tierConfig(); @endphp
                        <div class="flex items-start justify-between p-4 rounded-kid bg-kid-bg">
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">
                                    {{ app()->getLocale() === 'id' ? 'Level' : 'Level' }}
                                </div>
                                <div class="font-semibold flex items-center gap-2">
                                    <x-dynamic-component :component="'lucide-' . $tierCfg['icon']" class="w-4 h-4 text-kid-blue" stroke-width="2.5" />
                                    {{ $tierCfg['label_' . app()->getLocale()] }}
                                    <span class="text-gray-400 text-sm font-normal">· {{ $tierCfg['ages'] }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ app()->getLocale() === 'id'
                                        ? 'Hubungi orang tua untuk mengubah level.'
                                        : 'Ask your parent to change the level.' }}
                                </div>
                            </div>
                            <x-lucide-award class="w-5 h-5 text-gray-400" stroke-width="2" />
                        </div>
                    @endif
                </div>
            </div>

            {{-- ==================== SECURITY ==================== --}}
            @if(! auth()->user()->isStudent())
                <div x-show="tab === 'security'" x-cloak>
                    <h2 class="text-lg font-extrabold mb-4">
                        {{ app()->getLocale() === 'id' ? 'Keamanan' : 'Security' }}
                    </h2>

                    @if(session()->has('success_security'))
                        <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
                            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
                            {{ session('success_security') }}
                        </div>
                    @endif

                    <form wire:submit="updatePassword" class="space-y-5">

                                <div>
                <label class="block font-semibold mb-1 text-sm">
                    {{ app()->getLocale() === 'id' ? 'Kata Sandi Saat Ini' : 'Current Password' }}
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="currentPassword"
                           class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <x-lucide-eye x-show="!show" class="w-5 h-5" stroke-width="2" />
                        <x-lucide-eye-off x-show="show" x-cloak class="w-5 h-5" stroke-width="2" />
                    </button>
                </div>
                @error('currentPassword') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1 text-sm">
                    {{ app()->getLocale() === 'id' ? 'Kata Sandi Baru' : 'New Password' }}
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="newPassword"
                           class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <x-lucide-eye x-show="!show" class="w-5 h-5" stroke-width="2" />
                        <x-lucide-eye-off x-show="show" x-cloak class="w-5 h-5" stroke-width="2" />
                    </button>
                </div>
                @error('newPassword') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

                                    <div>
                <label class="block font-semibold mb-1 text-sm">
                    {{ app()->getLocale() === 'id' ? 'Konfirmasi Kata Sandi Baru' : 'Confirm New Password' }}
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="newPassword_confirmation"
                           class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <x-lucide-eye x-show="!show" class="w-5 h-5" stroke-width="2" />
                        <x-lucide-eye-off x-show="show" x-cloak class="w-5 h-5" stroke-width="2" />
                    </button>
                </div>
                @error('newPassword_confirmation') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>
                        <button type="submit" class="btn-kid bg-kid-blue text-white w-full md:w-auto">
                            {{ app()->getLocale() === 'id' ? 'Ubah Kata Sandi' : 'Change Password' }}
                        </button>

                    </form>
                </div>
            @endif

        </div>
    </div>
</div>

