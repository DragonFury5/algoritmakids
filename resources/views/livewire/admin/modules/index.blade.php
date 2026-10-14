<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-kid-blue font-bold text-sm inline-flex items-center gap-1 hover:underline">
                <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
                <span>{{ app()->getLocale() === 'id' ? 'Dasbor Admin' : 'Admin Dashboard' }}</span>
            </a>
            <h1 class="text-2xl md:text-3xl font-extrabold mt-2">
                {{ app()->getLocale() === 'id' ? 'Modul' : 'Modules' }}
            </h1>
            <p class="text-gray-500 mt-1 text-sm">
                {{ app()->getLocale() === 'id' ? 'Kelola daftar modul pembelajaran.' : 'Manage the list of learning modules.' }}
            </p>
        </div>

        <button wire:click="openCreate" class="btn-kid bg-kid-blue text-white gap-2">
            <x-lucide-plus class="w-5 h-5" stroke-width="3" />
            <span>{{ app()->getLocale() === 'id' ? 'Tambah Modul' : 'Add Module' }}</span>
        </button>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
            {{ session('success') }}
        </div>
    @endif

    {{-- List --}}
    <div class="bg-white rounded-kid border border-gray-200 overflow-hidden">
        @if($modules->isEmpty())
            <div class="p-10 text-center">
                <x-lucide-package-open class="w-12 h-12 mx-auto mb-3 text-gray-300" stroke-width="1.5" />
                <p class="text-gray-500">
                    {{ app()->getLocale() === 'id' ? 'Belum ada modul.' : 'No modules yet.' }}
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($modules as $module)
                    <div class="flex items-center gap-4 p-4 md:p-5 hover:bg-kid-bg/40 transition">
                        <div class="w-12 h-12 rounded-kid bg-kid-blue/10 flex items-center justify-center font-extrabold text-kid-blue shrink-0">
                            {{ $module->order }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-extrabold text-base md:text-lg truncate">
                                    {{ $module->title }}
                                </h3>
                                <span class="text-xs font-mono text-gray-400">/{{ $module->slug }}</span>
                            </div>
                            <p class="text-xs md:text-sm text-gray-500 truncate">{{ $module->description }}</p>
                        </div>

                        <div class="hidden md:flex items-center gap-1 text-sm text-gray-500 shrink-0">
                            <x-lucide-layers class="w-4 h-4 text-gray-400" stroke-width="2" />
                            {{ $module->levels_count }}
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="openEdit({{ $module->id }})"
                                    class="p-2 rounded-full hover:bg-kid-bg text-gray-500 hover:text-kid-blue transition">
                                <x-lucide-pencil class="w-4 h-4" stroke-width="2.5" />
                            </button>
                            <button wire:click="confirmDelete({{ $module->id }})"
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

            <div class="animate-pop-in bg-white rounded-t-kid md:rounded-kid shadow-2xl w-full md:max-w-lg
                        max-h-[92vh] md:max-h-[90vh] flex flex-col">

                <div class="flex justify-between items-center p-4 md:p-6 border-b border-gray-100 shrink-0">
                    <h2 class="text-lg md:text-xl font-extrabold">
                        {{ $editingId
                            ? (app()->getLocale() === 'id' ? 'Edit Modul' : 'Edit Module')
                            : (app()->getLocale() === 'id' ? 'Tambah Modul' : 'Add Module') }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-700 p-1">
                        <x-lucide-x class="w-5 h-5" stroke-width="2.5" />
                    </button>
                </div>

                <form wire:submit="save" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Slug</label>
                        <input type="text" wire:model="slug"
                               placeholder="e.g. sequencing"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm font-mono">
                        <p class="text-xs text-gray-400 mt-1">
                            {{ app()->getLocale() === 'id' ? 'Huruf kecil, angka, dan tanda hubung.' : 'Lowercase letters, numbers, hyphens.' }}
                        </p>
                        @error('slug') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-1 text-sm">Title (EN)</label>
                            <input type="text" wire:model="title_en"
                                   class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm">
                            @error('title_en') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-sm">Judul (ID)</label>
                            <input type="text" wire:model="title_id"
                                   class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm">
                            @error('title_id') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Description (EN)</label>
                        <textarea wire:model="description_en" rows="2"
                                  class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm"></textarea>
                        @error('description_en') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">Deskripsi (ID)</label>
                        <textarea wire:model="description_id" rows="2"
                                  class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm"></textarea>
                        @error('description_id') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-sm">
                            {{ app()->getLocale() === 'id' ? 'Urutan' : 'Order' }}
                        </label>
                        <input type="number" wire:model="order" min="0" max="999"
                               class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue text-sm">
                        @error('order') <span class="text-kid-coral text-xs">{{ $message }}</span> @enderror
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

    {{-- ==================== DELETE CONFIRMATION ==================== --}}
    @if($deletingId)
        @php $deleting = \App\Models\Module::withCount('levels')->find($deletingId); @endphp
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             wire:click.self="cancelDelete">
            <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-6 md:p-8 max-w-md w-full text-center">

                <div class="w-14 h-14 rounded-full bg-kid-coral/15 flex items-center justify-center mx-auto mb-4">
                    <x-lucide-alert-triangle class="w-7 h-7 text-kid-coral" stroke-width="2.5" />
                </div>

                <h2 class="text-xl font-extrabold mb-2">
                    {{ app()->getLocale() === 'id' ? 'Hapus Modul?' : 'Delete Module?' }}
                </h2>

                <p class="text-gray-500 mb-1 text-sm">
                    <strong>{{ $deleting?->title }}</strong>
                </p>

                @if($deleting && $deleting->levels_count > 0)
                    <p class="text-kid-coral text-sm mt-3 font-semibold">
                        {{ app()->getLocale() === 'id'
                            ? "⚠ Ini juga akan menghapus {$deleting->levels_count} level beserta progresnya!"
                            : "⚠ This will also delete {$deleting->levels_count} level(s) and their progress!" }}
                    </p>
                @endif

                <div class="flex gap-3 mt-6">
                    <button wire:click="cancelDelete"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-gray-100 text-gray-700 text-sm">
                        {{ app()->getLocale() === 'id' ? 'Batal' : 'Cancel' }}
                    </button>
                    <button wire:click="delete"
                            class="flex-1 rounded-kid px-4 py-2.5 font-bold bg-kid-coral text-white text-sm">
                        {{ app()->getLocale() === 'id' ? 'Hapus' : 'Delete' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>