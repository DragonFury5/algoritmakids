<div>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold">
                {{ app()->getLocale() === 'id' ? 'Dasbor Guru' : 'Teacher Dashboard' }}
            </h1>
            <p class="text-gray-500 mt-1">
                {{ app()->getLocale() === 'id' ? 'Kelola kelas dan pantau siswa Anda.' : 'Manage your classes and monitor students.' }}
            </p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="btn-kid bg-kid-blue text-white gap-2">
            <x-lucide-plus class="w-5 h-5" stroke-width="3" />
            <span>{{ app()->getLocale() === 'id' ? 'Buat Kelas' : 'Create Class' }}</span>
        </button>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-kid-mint/30 text-kid-text rounded-kid p-3 text-sm flex items-center gap-2">
            <x-lucide-check-circle class="w-4 h-4 text-kid-mint" stroke-width="2.5" />
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classrooms as $class)
            <div class="bg-white rounded-kid border border-gray-200 p-5 card-lift flex flex-col">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-kid bg-kid-purple flex items-center justify-center">
                        <x-lucide-users class="w-6 h-6 text-white" stroke-width="2.5" />
                    </div>
                    <div class="bg-kid-bg px-3 py-1 rounded-full text-xs font-mono font-bold text-gray-500">
                        {{ $class->code }}
                    </div>
                </div>
                <h3 class="text-xl font-extrabold mb-1">{{ $class->name }}</h3>
                <p class="text-sm text-gray-500 mb-6 flex-1">
                    {{ $class->students_count }} {{ app()->getLocale() === 'id' ? 'siswa terdaftar' : 'students enrolled' }}
                </p>
                <a href="{{ route('teacher.classroom', $class) }}" class="btn-kid bg-white border-2 border-gray-200 text-kid-text w-full justify-center">
                    {{ app()->getLocale() === 'id' ? 'Lihat Kelas' : 'View Class' }}
                </a>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-kid border border-gray-200 p-10 text-center text-gray-500">
                {{ app()->getLocale() === 'id' ? 'Belum ada kelas. Buat kelas pertama Anda!' : 'No classes yet. Create your first class!' }}
            </div>
        @endforelse
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="animate-pop-in bg-white rounded-kid shadow-2xl p-6 max-w-sm w-full">
                <h2 class="text-xl font-extrabold mb-4">{{ app()->getLocale() === 'id' ? 'Buat Kelas Baru' : 'Create New Class' }}</h2>
                <form wire:submit="createClassroom">
                    <label class="block font-semibold mb-1 text-sm">{{ app()->getLocale() === 'id' ? 'Nama Kelas' : 'Class Name' }}</label>
                    <input type="text" wire:model="newClassName" placeholder="e.g. 5A - Algoritma" class="w-full rounded-kid border-gray-300 focus:border-kid-blue focus:ring-kid-blue mb-4">
                    @error('newClassName') <span class="text-kid-coral text-xs block mb-2">{{ $message }}</span> @enderror
                    <div class="flex gap-2">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="flex-1 rounded-kid px-4 py-2 font-bold bg-gray-100 text-gray-700">Batal</button>
                        <button type="submit" class="flex-1 rounded-kid px-4 py-2 font-bold bg-kid-blue text-white">Buat</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>