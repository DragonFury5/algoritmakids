<?php

namespace App\Livewire\Parent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AddChild extends Component
{
    public bool $showModal = false;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $avatar = 'robot-mint';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'avatar' => 'required|in:robot-blue,robot-green,robot-yellow,robot-coral,robot-purple,robot-mint',
        ];
    }

    public function openModal(): void
    {
        $this->reset(['name', 'email', 'password', 'avatar']);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'student',
            'avatar' => $this->avatar,
            'language' => auth()->user()->language,
            'parent_id' => auth()->id(),
            'sound_enabled' => true,
        ]);

        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'avatar']);

        $this->dispatch('child-added');
        session()->flash('success', app()->getLocale() === 'id'
            ? 'Akun anak berhasil dibuat!'
            : 'Child account created successfully!');
    }

    public function render()
    {
        return view('livewire.parent.add-child');
    }
}