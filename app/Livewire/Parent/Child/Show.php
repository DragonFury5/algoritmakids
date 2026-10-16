<?php

namespace App\Livewire\Parent\Child;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public User $child;

    // Edit fields
    public string $name = '';
    public string $avatar = '';
    public string $tier = '';

    // Password reset
    public string $newPassword = '';

    // Delete
    public bool $showDeleteModal = false;

    public function mount(User $child): void
    {
        // Ensure the child belongs to the logged-in parent
        if ($child->parent_id !== auth()->id()) {
            abort(403);
        }
        if (! $child->isStudent()) {
            abort(404);
        }

        $this->child = $child;
        $this->name = $child->name;
        $this->avatar = $child->avatar;
        $this->tier = $child->tier;
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name'   => 'required|string|min:2|max:60',
            'avatar' => 'required|in:robot-blue,robot-green,robot-yellow,robot-coral,robot-purple,robot-mint',
            'tier'   => 'required|in:rookie,explorer,pro,hacker',
        ]);

        $this->child->update([
            'name'   => $this->name,
            'avatar' => $this->avatar,
            'tier'   => $this->tier,
        ]);

        session()->flash('success_profile', app()->getLocale() === 'id'
            ? 'Profil anak diperbarui.'
            : 'Child profile updated.');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'newPassword' => 'required|string|min:6',
        ]);

        $this->child->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->newPassword = '';

        session()->flash('success_password', app()->getLocale() === 'id'
            ? 'Kata sandi anak berhasil direset.'
            : 'Child password reset.');
    }

    public function togglePause(): void
    {
        if ($this->child->isPaused()) {
            $this->child->resume();
            session()->flash('success_status', app()->getLocale() === 'id'
                ? 'Akun anak diaktifkan kembali.'
                : 'Child account resumed.');
        } else {
            $this->child->pause();
            session()->flash('success_status', app()->getLocale() === 'id'
                ? 'Akun anak dijeda. Mereka tidak bisa masuk.'
                : 'Child account paused. They cannot log in.');
        }
    }

    public function confirmDelete(): void
    {
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
    }

    public function delete(): void
    {
        $this->child->delete();
        session()->flash('success', app()->getLocale() === 'id'
            ? 'Akun anak dihapus.'
            : 'Child account deleted.');

        $this->redirectRoute('parent.dashboard');
    }

    public function render()
    {
        $child = $this->child->fresh();

        $progress = $child->progress()->with('level.module')->get();

        $stats = [
            'stars'    => (int) $progress->sum('stars'),
            'played'   => $progress->count(),
            'finished' => $progress->whereNotNull('completed_at')->count(),
            'mastered' => $progress->where('stars', 3)->count(),
            'badges'   => $child->badges()->count(),
        ];

        return view('livewire.parent.child.show', [
            'progress' => $progress,
            'stats'    => $stats,
        ]);
    }
}