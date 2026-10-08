<div>
    {{-- Trigger button --}}
    <button wire:click="openModal" class="btn-kid bg-kid-teal text-lg">
        + {{ app()->getLocale() === 'id' ? 'Tambah Anak' : 'Add Child' }}
    </button>

    {{-- Success message --}}
    @if (session()->has('success'))
        <div class="mt-4 bg-kid-teal/50 text-kid-text rounded-kid p-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
             wire:click.self="closeModal">
            <div class="bg-white rounded-kid shadow-2xl w-full max-w-md p-8">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-extrabold">
                        {{ app()->getLocale() === 'id' ? 'Tambah Akun Anak' : 'Add Child Account' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
                </div>

                <form wire:submit="save" class="space-y-4">

                    <div>
                        <label class="block font-semibold mb-1">
                            {{ app()->getLocale() === 'id' ? 'Nama Anak' : 'Child\'s Name' }}
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
                        <label class="block font-semibold mb-1">
                            {{ app()->getLocale() === 'id' ? 'Kata Sandi' : 'Password' }}
                        </label>
                        <input type="password" wire:model="password"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-coral focus:ring-kid-coral">
                        @error('password') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">
                            {{ app()->getLocale() === 'id' ? 'Avatar' : 'Avatar' }}
                        </label>
                        <div class="flex flex-wrap gap-3">
                            @foreach(['robot-blue','robot-green','robot-yellow','robot-coral','robot-purple','robot-mint'] as $a)
                                <button type="button" wire:click="$set('avatar', '{{ $a }}')"
                                    class="w-12 h-12 rounded-full border-4 transition
                                           {{ $avatar === $a ? 'border-kid-coral scale-110' : 'border-transparent' }}"
                                    style="background-color: {{ ['robot-blue' => '#7EC8E3','robot-green' => '#A8E6CF','robot-yellow' => '#FFD97D','robot-coral' => '#FF8B94','robot-purple' => '#B39DDB','robot-mint' => '#B8E0D2'][$a] }}">
                                </button>
                            @endforeach
                        </div>
                        @error('avatar') <span class="text-kid-coral text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                                class="flex-1 rounded-kid px-4 py-3 font-bold bg-gray-200 text-gray-700">
                            {{ app()->getLocale() === 'id' ? 'Batal' : 'Cancel' }}
                        </button>
                        <button type="submit" class="flex-1 rounded-kid px-4 py-3 font-bold bg-kid-teal text-white">
                            {{ app()->getLocale() === 'id' ? 'Simpan' : 'Save' }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    @endif
</div>