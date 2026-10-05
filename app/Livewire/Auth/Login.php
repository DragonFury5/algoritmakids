<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function login()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', app()->getLocale() === 'id'
                ? 'Email atau kata sandi salah.'
                : 'Invalid email or password.');
            return;
        }

        $user = Auth::user();

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') :
            ($user->isParent() ? route('parent.dashboard') : route('student.dashboard'))
        );
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}