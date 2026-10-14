<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
                <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
                <span>{{ app()->getLocale() === 'id' ? 'Dasbor Admin' : 'Admin Dashboard' }}</span>
            </a>
            <h1 class="text-2xl md:text-3xl font-extrabold mt-2">
                {{ app()->getLocale() === 'id' ? 'Level' : 'Levels' }}
            </h1>
            <p class="text-gray-500 mt-1 text-sm">
                {{ app()->getLocale() === 'id' ? 'Kelola semua level permainan.' : 'Manage all game levels.' }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <a href="{{ route('admin.modules') }}"
               class="btn-kid bg-white border-2 border-gray-200 text-kid-text gap-2 text-sm flex-1 md:flex-none justify-center">
                <x-lucide-layers class="w-4 h-4" stroke-width="2.5" />
                <span>{{ app()->getLocale() === 'id' ? 'Modul' : 'Modules' }}</span>
            </a>
            <button wire:click="openCreate"
                    class="btn-kid bg-kid-blue text-white gap-2 text-sm flex-1 md:flex-none justify-center">
                <x-lucide-plus class="w-4 h-4" stroke-width="3" />
                <span>{{ app()->getLocale() === 'id' ? 'Tambah Level' : 'Add Level' }}</span>
            </button>
        </div>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-kid border border-gray-200 p-3 md:p-4 mb-4 flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
            <label class="text-xs uppercase tracking-wider font-extrabold text-gray-400">Module</label>
            <select wire:model.live="filterModule"
                    class="rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-1.5">
                <option value="">— {{ app()->getLocale() === 'id' ? 'Semua' : 'All' }} —</option>
                @foreach($modules as $m)
                    <option value="{{ $m->id }}">{{ $m->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <label class="text-xs uppercase tracking-wider font-extrabold text-gray-400">Tier</label>
            <select wire:model.live="filterTier"
                    class="rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-1.5">
                <option value="">— {{ app()->getLocale() === 'id' ? 'Semua' : 'All' }} —</option>
                @foreach($tiers as $key => $t)
                    <option value="{{ $key }}">{{ $t['label_' . app()->getLocale()] }}</option>
                @endforeach
            </select>
        </div>

        @if($filterModule || $filterTier)
            <button wire:click="clearFilters"
                    class="text-xs font-bold text-kid-coral hover:underline ml-auto">
                {{ app()->getLocale() === 'id' ? 'Hapus filter' : 'Clear filters' }}
            </button>
        @endif
    </div>

    {{-- List --}}
    <div class="bg-white rounded-kid border border-gray-200 overflow-hidden">
        @if($levels->isEmpty())
            <div class="p-10 text-center">
                <x-lucide-package-open class="w-12 h-12 mx-auto mb-3 text-gray-300" stroke-width="1.5" />
                <p class="text-gray-500">
                    {{ app()->getLocale() === 'id' ? 'Belum ada level yang cocok.' : 'No matching levels.' }}
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($levels as $level)
                    @php
                        $tierCfg = config('tiers.' . $level->tier);
                        $colorMap = [
                            'mint' => 'bg-kid-mint', 'yellow' => 'bg-kid-yellow',
                            'coral' => 'bg-kid-coral', 'purple' => 'bg-kid-purple',
                        ];
                        $tierBg = $colorMap[$tierCfg['color']] ?? 'bg-kid-mint';
                    @endphp
                    <div class="flex items-center gap-3 md:gap-4 p-3 md:p-4 hover:bg-kid-bg/40 transition">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-kid bg-kid-blue/10 flex items-center justify-center font-extrabold text-kid-blue shrink-0">
                            {{ $level->order }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm md:text-base truncate">{{ $level->title }}</span>
                                <span class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-full text-white {{ $tierBg }}">
                                    <x-dynamic-component :component="'lucide-' . $tierCfg['icon']" class="w-3 h-3" stroke-width="3" />
                                    {{ $tierCfg['label_' . app()->getLocale()] }}
                                </span>
                            </div>
                            <div class="text-xs md:text-sm text-gray-500 truncate">
                                {{ $level->module?->title ?? '—' }}
                                <span class="text-gray-300">·</span>
                                <span class="font-mono text-[11px]">{{ $level->slug }}</span>
                            </div>
                        </div>

                        <span class="hidden md:inline-flex text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-full shrink-0
                                     {{ $level->game_type === 'robot_path' ? 'bg-kid-blue/15 text-kid-blue' : 'bg-kid-purple/15 text-kid-purple' }}">
                            {{ $level->game_type === 'robot_path' ? 'Robot' : 'Pattern' }}
                        </span>

                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ $level->playRoute() }}" target="_blank"
                               class="p-2 rounded-full hover:bg-kid-bg text-gray-500 hover:text-kid-blue transition"
                               title="{{ app()->getLocale() === 'id' ? 'Pratinjau' : 'Preview' }}">
                                <x-lucide-external-link class="w-4 h-4" stroke-width="2.5" />
                            </a>
                            <button wire:click="openEdit({{ $level->id }})"
                                    class="p-2 rounded-full hover:bg-kid-bg text-gray-500 hover:text-kid-blue transition">
                                <x-lucide-pencil class="w-4 h-4" stroke-width="2.5" />
                            </button>
                            <button wire:click="confirmDelete({{ $level->id }})"
                                    class="p-2 rounded-full hover:bg-kid-coral/10 text-gray-500 hover:text-kid-coral transition">
                                <x-lucide-trash-2 class="w-4 h-4" stroke-width="2.5" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ==================== CREATE / EDIT MODAL ==================== --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-0 md:p-4"
             wire:click.self="closeModal">

            <div class="animate-pop-in bg-white rounded-t-kid md:rounded-kid shadow-2xl w-full md:max-w-3xl
                        max-h-[92vh] md:max-h-[90vh] flex flex-col">

                <div class="flex justify-between items-center p-4 md:p-6 border-b border-gray-100 shrink-0">
                    <h2 class="text-lg md:text-xl font-extrabold">
                        {{ $editingId
                            ? (app()->getLocale() === 'id' ? 'Edit Level' : 'Edit Level')
                            : (app()->getLocale() === 'id' ? 'Tambah Level' : 'Add Level') }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-700 p-1">
                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                    </button>
                </div>

                <form wire:submit="save" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-1 text-sm">
                                {{ app()->getLocale() === 'id' ? 'Modul' : 'Module' }}
                            </label>
                            <select wire:model="module_id"
                                    class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                                <option value="">— {{ app()->getLocale() === 'id' ? 'Pilih' : 'Choose' }} —</option>
                                @foreach($modules as $m)
                                    <option value="{{ $m->id }}">{{ $m->title }}</option>
                                @endforeach
                            </select>
                            @error('module_id') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold mb-1 text-sm">
                                {{ app()->getLocale() === 'id' ? 'Level' : 'Tier' }}
                            </label>
                            <select wire:model="tier"
                                    class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                                @foreach($tiers as $key => $t)
                                    <option value="{{ $key }}">{{ $t['label_' . app()->getLocale()] }} · {{ $t['ages'] }}</option>
                                @endforeach
                            </select>
                            @error('tier') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Jenis Permainan' : 'Game Type' }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="$set('game_type', 'robot_path')"
                                    class="p-3 rounded-kid border-2 text-left transition
                                           {{ $game_type === 'robot_path' ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                                <div class="font-extrabold text-sm flex items-center gap-2">
                                    <x-lucide-bot class="w-4 h-4" stroke-width="2.5" />
                                    Robot Path
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ app()->getLocale() === 'id' ? 'Puzzle blok' : 'Block puzzle' }}
                                </div>
                            </button>
                            <button type="button" wire:click="$set('game_type', 'pattern_fixer')"
                                    class="p-3 rounded-kid border-2 text-left transition
                                           {{ $game_type === 'pattern_fixer' ? 'border-kid-blue bg-kid-blue/5' : 'border-gray-200' }}">
                                <div class="font-extrabold text-sm flex items-center gap-2">
                                    <x-lucide-bug class="w-4 h-4" stroke-width="2.5" />
                                    Pattern Fixer
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ app()->getLocale() === 'id' ? 'Perbaiki bug' : 'Debug puzzle' }}
                                </div>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Slug</label>
                        <input type="text" wire:model="slug"
                               placeholder="e.g. maze-turn"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm font-mono py-2">
                        @error('slug') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-1 text-sm">Title (EN)</label>
                            <input type="text" wire:model="title_en"
                                   class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                            @error('title_en') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-sm">Judul (ID)</label>
                            <input type="text" wire:model="title_id"
                                   class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                            @error('title_id') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-1 text-sm">Instructions (EN)</label>
                            <textarea wire:model="instructions_en" rows="2"
                                      class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm"></textarea>
                            @error('instructions_en') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-sm">Instruksi (ID)</label>
                            <textarea wire:model="instructions_id" rows="2"
                                      class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm"></textarea>
                            @error('instructions_id') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Urutan' : 'Order' }}
                        </label>
                        <input type="number" wire:model="order" min="0" max="999"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm py-2">
                        @error('order') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Game Data JSON --}}
                    <div x-data="{
                        json: @entangle('game_data_json'),
                        get valid() {
                            try { JSON.parse(this.json); return true; } catch (e) { return false; }
                        },
                        get error() {
                            try { JSON.parse(this.json); return null; } catch (e) { return e.message; }
                        }
                    }">
                        <div class="flex items-center justify-between mb-2">
                            <label class="font-semibold text-sm">Game Data (JSON)</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] uppercase tracking-wider font-extrabold"
                                      :class="valid ? 'text-kid-mint' : 'text-kid-coral'"
                                      x-text="valid ? '✓ Valid' : '✗ Invalid'"></span>
                                <div class="relative" x-data="{ open: false }">
                                    <button type="button" @click="open = !open"
                                            class="text-xs font-bold text-kid-blue hover:underline flex items-center gap-1">
                                        <x-lucide-sparkles class="w-3 h-3" stroke-width="2.5" />
                                        {{ app()->getLocale() === 'id' ? 'Template' : 'Template' }}
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                         class="absolute right-0 top-full mt-1 z-30 bg-white rounded-kid border border-gray-200 shadow-lg p-2 min-w-[200px]">
                                        <button type="button" @click="open = false; $wire.loadTemplate('robot_path')"
                                                class="w-full text-left px-3 py-2 rounded-kid hover:bg-kid-bg text-sm">
                                            Robot Path
                                        </button>
                                        <button type="button" @click="open = false; $wire.loadTemplate('pattern_fixer')"
                                                class="w-full text-left px-3 py-2 rounded-kid hover:bg-kid-bg text-sm">
                                            Pattern Fixer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <textarea wire:model.live.debounce.500ms="game_data_json" rows="12"
                                  class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-xs font-mono py-2"
                                  :class="valid ? '' : 'border-kid-coral'"
                                  spellcheck="false"></textarea>
                        <p x-show="!valid" x-cloak class="text-kid-coral text-xs mt-1" x-text="error"></p>
                        @error('game_data_json') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                </form>

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

    {{-- ==================== DELETE MODAL ==================== --}}
    @if($showDeleteModal && $deletingId)
        @php $deleting = \App\Models\Level::withCount('progress')->find($deletingId); @endphp
        @if($deleting)
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                 wire:click.self="cancelDelete">
                <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-6 md:p-8 max-w-md w-full text-center">

                    <div class="w-16 h-16 rounded-full bg-kid-coral/15 flex items-center justify-center mx-auto mb-4 animate-pulse-soft">
                        <x-lucide-alert-triangle class="w-8 h-8 text-kid-coral" stroke-width="2.5" />
                    </div>

                    <h2 class="text-xl md:text-2xl font-extrabold mb-2">
                        {{ app()->getLocale() === 'id' ? 'Hapus Level?' : 'Delete Level?' }}
                    </h2>

                    <div class="bg-kid-bg rounded-kid p-3 my-4">
                        <div class="font-bold text-sm">{{ $deleting->title }}</div>
                        <div class="text-xs text-gray-500 font-mono truncate">{{ $deleting->slug }}</div>
                    </div>

                    @if($deleting->progress_count > 0)
                        <div class="bg-kid-coral/10 rounded-kid p-3 text-xs text-left">
                            <div class="font-bold text-kid-coral mb-1">
                                {{ app()->getLocale() === 'id' ? '⚠ Peringatan' : '⚠ Warning' }}
                            </div>
                            {{ app()->getLocale() === 'id'
                                ? "{$deleting->progress_count} catatan progres siswa akan hilang."
                                : "{$deleting->progress_count} student progress record(s) will be lost." }}
                        </div>
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
</div>