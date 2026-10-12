<div>
    {{-- Trigger button --}}
    <button wire:click="openModal" class="btn-kid bg-kid-coral text-white text-lg gap-2">
        <x-lucide-plus class="w-5 h-5" stroke-width="3" />
        <span>{{ app()->getLocale() === 'id' ? 'Tambah Anak' : 'Add Child' }}</span>
    </button>

    {{-- Success message --}}
    @if (session()->has('success'))
        <div class="mt-4 bg-kid-mint/30 text-kid-text rounded-kid p-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-0 md:p-4"
             wire:click.self="closeModal">

            <div class="animate-pop-in bg-white rounded-t-kid md:rounded-kid shadow-2xl w-full md:max-w-md
                        max-h-[92vh] md:max-h-[90vh] flex flex-col">

                {{-- Sticky header --}}
                <div class="flex justify-between items-center p-4 md:p-6 border-b border-gray-100 shrink-0">
                    <h2 class="text-lg md:text-xl font-extrabold">
                        {{ app()->getLocale() === 'id' ? 'Tambah Akun Anak' : 'Add Child Account' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-700 p-1">
                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                    </button>
                </div>

                {{-- Scrollable body --}}
                <form wire:submit="save" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">

                    <div>
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Nama Anak' : 'Child\'s Name' }}
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
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Kata Sandi' : 'Password' }}
                        </label>
                        <input type="password" wire:model="password"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                        @error('password') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Avatar --}}
                    <div>
                        <label class="block font-semibold mb-2 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Avatar' : 'Avatar' }}
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['robot-blue','robot-green','robot-yellow','robot-coral','robot-purple','robot-mint'] as $a)
                                <button type="button" wire:click="$set('avatar', '{{ $a }}')"
                                    class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 md:border-4 transition
                                           {{ $avatar === $a ? 'border-kid-blue scale-110' : 'border-transparent' }}"
                                    style="background-color: {{ ['robot-blue' => '#7EC8E3','robot-green' => '#A8E6CF','robot-yellow' => '#FFD97D','robot-coral' => '#FF8B94','robot-purple' => '#B39DDB','robot-mint' => '#B8E0D2'][$a] }}">
                                </button>
                            @endforeach
                        </div>
                        @error('avatar') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tier --}}
                    <div>
                        <label class="block font-semibold mb-2 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Pilih Level' : 'Choose Level' }}
                        </label>
                        <div class="space-y-2">
                            @foreach(config('tiers') as $key => $tierData)
                                <button type="button" wire:click="$set('tier', '{{ $key }}')"
                                        class="w-full flex items-center gap-2 md:gap-3 p-2 md:p-3 rounded-kid border-2 text-left transition
                                               {{ $tier === $key ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200 hover:border-gray-300' }}">
                                    @php
                                        $colorMap = [
                                            'mint' => 'bg-kid-mint',
                                            'yellow' => 'bg-kid-yellow',
                                            'coral' => 'bg-kid-coral',
                                            'purple' => 'bg-kid-purple',
                                        ];
                                    @endphp
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full {{ $colorMap[$tierData['color']] }} flex items-center justify-center text-white shrink-0">
                                        <x-dynamic-component :component="'lucide-' . $tierData['icon']" class="w-4 h-4 md:w-5 md:h-5" stroke-width="2.5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-extrabold text-xs md:text-sm">
                                            {{ $tierData['label_' . app()->getLocale()] }}
                                            <span class="text-xs text-gray-400 font-normal">· {{ $tierData['ages'] }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 truncate">
                                            {{ $tierData['grades_' . app()->getLocale()] }}
                                        </div>
                                    </div>
                                    @if($tier === $key)
                                        <x-lucide-check class="w-4 h-4 md:w-5 md:h-5 text-kid-blue shrink-0" stroke-width="3" />
                                    @endif
                                </button>
                            @endforeach
                        </div>
                        @error('tier') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                </form>

                {{-- Sticky footer --}}
                <div class="p-4 md:p-6 border-t border-gray-100 flex gap-3 shrink-0">
                    <button type="button" wire:click="closeModal"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-gray-100 text-gray-700 text-sm">
                        {{ app()->getLocale() === 'id' ? 'Batal' : 'Cancel' }}
                    </button>
                    <button type="button" wire:click="save"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-kid-blue text-white text-sm">
                        {{ app()->getLocale() === 'id' ? 'Simpan' : 'Save' }}
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>