<div class="min-h-screen flex items-center justify-center py-10 px-4">
    <div class="animate-pop-in w-full max-w-md bg-white rounded-kid shadow-lg p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-kid-text">
                {{ app()->getLocale() === 'id' ? 'Masuk' : 'Log In' }}
            </h1>
            <p class="text-gray-500 mt-2">
                {{ app()->getLocale() === 'id' ? 'Selamat datang kembali!' : 'Welcome back!' }}
            </p>
        </div>

        <form wire:submit="login" class="space-y-5">

            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" wire:model="email"
                       class="w-full rounded-kid border-gray-300 focus:border-kid-coral focus:ring-kid-coral">
                @error('email') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
            </div>

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

            <label class="flex items-center gap-2 cursor-pointer text-sm">
                <input type="checkbox" wire:model="remember" class="rounded text-kid-coral">
                {{ app()->getLocale() === 'id' ? 'Ingat saya' : 'Remember me' }}
            </label>

            <button type="submit" class="btn-kid bg-kid-teal w-full text-lg">
                {{ app()->getLocale() === 'id' ? 'Masuk' : 'Log In' }}
            </button>

        </form>

        <p class="text-center mt-6 text-gray-600">
            {{ app()->getLocale() === 'id' ? 'Belum punya akun?' : 'Don\'t have an account?' }}
            <a href="{{ route('register') }}" class="text-kid-coral font-bold underline">
                {{ app()->getLocale() === 'id' ? 'Daftar' : 'Sign Up' }}
            </a>
        </p>

    </div>
</div>