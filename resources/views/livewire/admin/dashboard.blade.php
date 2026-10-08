<div>
    <h1 class="text-3xl font-extrabold mb-2">
        {{ app()->getLocale() === 'id' ? 'Dasbor Admin' : 'Admin Dashboard' }}
    </h1>
    <p class="text-gray-500 mb-8">
        {{ app()->getLocale() === 'id' ? 'Kelola pengguna platform.' : 'Manage platform users.' }}
    </p>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-kid shadow-md p-6 text-center">
            <div class="text-4xl font-extrabold text-kid-coral">{{ $totalParents }}</div>
            <div class="text-gray-500">{{ app()->getLocale() === 'id' ? 'Orang Tua' : 'Parents' }}</div>
        </div>
        <div class="bg-white rounded-kid shadow-md p-6 text-center">
            <div class="text-4xl font-extrabold text-kid-coral">{{ $totalStudents }}</div>
            <div class="text-gray-500">{{ app()->getLocale() === 'id' ? 'Siswa' : 'Students' }}</div>
        </div>
        <div class="bg-white rounded-kid shadow-md p-6 text-center">
            <div class="text-4xl font-extrabold text-kid-purple">{{ $users->count() }}</div>
            <div class="text-gray-500">{{ app()->getLocale() === 'id' ? 'Total' : 'Total' }}</div>
        </div>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-kid-teal/50 rounded-kid p-4">{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="mb-4 bg-kid-teal/30 rounded-kid p-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-kid shadow-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-kid-bg text-left">
                <tr>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Parent</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-gray-100">
                        <td class="p-4 font-semibold">{{ $user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $user->isAdmin() ? 'bg-kid-purple/30' : ($user->isParent() ? 'bg-kid-teal/30' : 'bg-kid-teal/50') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500">{{ $user->parent?->name ?? '—' }}</td>
                        <td class="p-4 text-right">
                            @if($user->id !== auth()->id())
                                <button wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Delete this user?"
                                        class="text-kid-coral font-bold hover:underline">
                                    Delete
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>