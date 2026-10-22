<div class="min-h-screen flex items-center justify-center py-10 px-4">
    <div class="animate-pop-in w-full max-w-lg bg-white rounded-kid shadow-lg p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-kid-text">
                {{ app()->getLocale() === 'id' ? 'Buat Akun Orang Tua' : 'Create Parent Account' }}
            </h1>
            <p class="text-gray-500 mt-2">
                {{ app()->getLocale() === 'id' ? 'Daftar untuk membuat akun anak Anda.' : 'Sign up to create your child\'s account.' }}
            </p>
        </div>

        <form wire:submit="register" class="space-y-5">

            <div>
                <label class="block font-semibold mb-1">
                    {{ app()->getLocale() === 'id' ? 'Nama' : 'Name' }}
                </label>
                <input type="text" wire:model="name"
                       class="w-full rounded-kid border-gray-300 focus:border-kid-coral focus:ring-kid-coral">
                @error('name') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" wire:model="email"
                       class="w-full rounded-kid border-gray-300 focus:border-kid-coral focus:ring-kid-coral">
                @error('email') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                      <div>
                <label class="block font-semibold mb-1">
                    {{ app()->getLocale() === 'id' ? 'Kata Sandi' : 'Password' }}
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="password"
                           class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <x-lucide-eye x-show="!show" class="w-5 h-5" stroke-width="2" />
                        <x-lucide-eye-off x-show="show" x-cloak class="w-5 h-5" stroke-width="2" />
                    </button>
                </div>
                @error('password') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

                   <div>
                <label class="block font-semibold mb-1">
                    {{ app()->getLocale() === 'id' ? 'Konfirmasi Kata Sandi' : 'Confirm Password' }}
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation"
                           class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <x-lucide-eye x-show="!show" class="w-5 h-5" stroke-width="2" />
                        <x-lucide-eye-off x-show="show" x-cloak class="w-5 h-5" stroke-width="2" />
                    </button>
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Pilih Avatar' : 'Choose Avatar' }}
                </label>
                <div class="flex flex-wrap gap-3">
                    @foreach(['robot-blue','robot-green','robot-yellow','robot-coral','robot-purple','robot-mint'] as $a)
                        <button type="button" wire:click="$set('avatar', '{{ $a }}')"
                            class="w-14 h-14 rounded-full border-4 transition
                                   {{ $avatar === $a ? 'border-kid-coral scale-110' : 'border-transparent' }}"
                            style="background-color: {{ ['robot-blue' => '#7EC8E3','robot-green' => '#A8E6CF','robot-yellow' => '#FFD97D','robot-coral' => '#FF8B94','robot-purple' => '#B39DDB','robot-mint' => '#B8E0D2'][$a] }}">
                        </button>
                    @endforeach
                </div>
                @error('avatar') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1">
                    {{ app()->getLocale() === 'id' ? 'Bahasa' : 'Language' }}
                </label>
                <select wire:model="language"
                        class="w-full rounded-kid border-gray-300 focus:border-kid-coral focus:ring-kid-coral">
                    <option value="id">Bahasa Indonesia</option>
                    <option value="en">English</option>
                </select>
            </div>

            <div class="bg-kid-bg rounded-kid p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="consent" class="mt-1 rounded text-kid-coral">
                    <span class="text-sm text-gray-700">
                        {{ app()->getLocale() === 'id'
                            ? 'Saya adalah orang tua/wali dan menyetujui anak saya menggunakan situs ini. Saya telah membaca '
                            : 'I am the parent/guardian and consent to my child using this site. I have read the ' }}
                        <a href="{{ route('privacy') }}" target="_blank" class="text-kid-coral underline font-semibold">
                            {{ app()->getLocale() === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}
                        </a>.
                    </span>
                </label>
                @error('consent') <span class="text-kid-coral text-sm block mt-2">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-kid bg-kid-teal w-full text-lg">
                {{ app()->getLocale() === 'id' ? 'Daftar' : 'Sign Up' }}
            </button>

        </form>

        <p class="text-center mt-6 text-gray-600">
            {{ app()->getLocale() === 'id' ? 'Sudah punya akun?' : 'Already have an account?' }}
            <a href="{{ route('login') }}" class="text-kid-coral font-bold underline">
                {{ app()->getLocale() === 'id' ? 'Masuk' : 'Log In' }}
            </a>
        </p>

    </div>
</div>