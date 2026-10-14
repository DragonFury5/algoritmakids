<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    // Edit
    public bool $showEditModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $email = '';
    public string $avatar = 'robot-blue';
    public string $role = 'student';
    public string $tier = 'rookie';
    public string $newPassword = '';

    // View
    public bool $showViewModal = false;
    public ?int $viewingId = null;

    // Delete
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|min:2|max:60',
            'email'       => 'required|email|unique:users,email,' . ($this->editingId ?? 'NULL'),
            'avatar'      => 'required|in:robot-blue,robot-green,robot-yellow,robot-coral,robot-purple,robot-mint',
            'role'        => 'required|in:admin,parent,student',
            'tier'        => 'required|in:rookie,explorer,pro,hacker',
            'newPassword' => 'nullable|string|min:8',
        ];
    }

    // ----- Edit -----

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId   = $user->id;
        $this->name        = $user->name;
        $this->email       = $user->email;
        $this->avatar      = $user->avatar;
        $this->role        = $user->role;
        $this->tier        = $user->tier;
        $this->newPassword = '';
        $this->showEditModal = true;
    }

    public function closeEdit(): void
    {
        $this->showEditModal = false;
        $this->resetErrorBag();
        $this->reset(['editingId', 'name', 'email', 'avatar', 'role', 'tier', 'newPassword']);
    }

    public function saveEdit(): void
    {
        $this->validate();

        $user = User::findOrFail($this->editingId);

        if ($user->id === auth()->id() && $this->role !== 'admin') {
            $this->addError('role', app()->getLocale() === 'id'
                ? 'Anda tidak dapat mengubah peran Anda sendiri.'
                : 'You cannot change your own role.');
            return;
        }

        $data = [
            'name'   => $this->name,
            'email'  => $this->email,
            'avatar' => $this->avatar,
            'role'   => $this->role,
        ];

        if ($this->role === 'student') {
            $data['tier'] = $this->tier;
        }

        if ($this->newPassword !== '') {
            $data['password'] = Hash::make($this->newPassword);
        }

        $user->update($data);

        session()->flash('success', app()->getLocale() === 'id' ? 'Pengguna diperbarui.' : 'User updated.');
        $this->closeEdit();
    }

    // ----- View -----

    public function openView(int $id): void
    {
        $this->viewingId = $id;
        $this->showViewModal = true;
    }

    public function closeView(): void
    {
        $this->showViewModal = false;
        $this->viewingId = null;
    }

    // ----- Delete -----

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
        $this->showDeleteModal = false;
    }

    public function delete(): void
    {
        if (! $this->deletingId) return;

        $user = User::findOrFail($this->deletingId);

        if ($user->id === auth()->id()) {
            session()->flash('error', app()->getLocale() === 'id'
                ? 'Anda tidak dapat menghapus akun Anda sendiri.'
                : 'You cannot delete yourself.');
            $this->cancelDelete();
            return;
        }

        $user->delete();
        session()->flash('success', app()->getLocale() === 'id' ? 'Pengguna dihapus.' : 'User deleted.');
        $this->cancelDelete();
    }

    // ----- Render -----

    public function render()
    {
        $users = User::orderBy('role')->orderBy('name')->get();

        // Data for the view modal
        $viewingUser = null;
        $viewingStats = null;
        $viewingChildren = null;

        if ($this->showViewModal && $this->viewingId) {
            $viewingUser = User::find($this->viewingId);

            if ($viewingUser) {
                if ($viewingUser->isStudent()) {
                    $progress = $viewingUser->progress()->get();
                    $viewingStats = [
                        'played'   => $progress->count(),
                        'finished' => $progress->whereNotNull('completed_at')->count(),
                        'mastered' => $progress->where('stars', 3)->count(),
                        'stars'    => (int) $progress->sum('stars'),
                        'attempts' => (int) $progress->sum('attempts'),
                        'badges'   => $viewingUser->badges()->count(),
                    ];
                } elseif ($viewingUser->isParent()) {
                    $viewingChildren = $viewingUser->children()
                        ->withCount('progress')
                        ->orderBy('name')
                        ->get()
                        ->map(function ($child) {
                            $child->stars    = (int) $child->progress()->sum('stars');
                            $child->finished = $child->progress()->whereNotNull('completed_at')->count();
                            return $child;
                        });
                }
            }
        }

        return view('livewire.admin.dashboard', [
            'users'           => $users,
            'totalParents'    => User::where('role', 'parent')->count(),
            'totalStudents'   => User::where('role', 'student')->count(),
            'viewingUser'     => $viewingUser,
            'viewingStats'    => $viewingStats,
            'viewingChildren' => $viewingChildren,
        ]);
    }
}