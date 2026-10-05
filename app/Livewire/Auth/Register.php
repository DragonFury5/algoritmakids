<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $avatar = 'robot-blue';
    public string $language = 'id';
    public bool $consent = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'required|in:robot-blue,robot-green,robot-yellow,robot-coral,robot-purple,robot-mint',
            'language' => 'required|in:id,en',
            'consent' => 'accepted',
        ];
    }

    protected function messages(): array
    {
        return [
            'consent.accepted' => 'You must consent before creating an account.',
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'parent',
            'avatar' => $this->avatar,
            'language' => $this->language,
            'consent_given_at' => now(),
            'sound_enabled' => true,
        ]);

        Auth::login($user);

        return redirect()->route('parent.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}