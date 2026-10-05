<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted.');
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'users' => User::orderBy('role')->orderBy('name')->get(),
            'totalParents' => User::where('role', 'parent')->count(),
            'totalStudents' => User::where('role', 'student')->count(),
        ]);
    }
}