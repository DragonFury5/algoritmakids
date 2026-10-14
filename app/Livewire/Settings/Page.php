<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Page extends Component
{
    // Profile
    public string $name = '';
    public string $avatar = '';

    // Preferences
    public string $language = 'id';
    public bool $soundEnabled = true;

    // Security
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->avatar = $user->avatar;
        $this->language = $user->language;
        $this->soundEnabled = (bool) $user->sound_enabled;
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $rules = [
            'avatar' => 'required|in:robot-blue,robot-green,robot-yellow,robot-coral,robot-purple,robot-mint',
        ];

     

        $this->validate($rules);


        $user->avatar = $this->avatar;
        $user->save();

        $this->dispatch('$refresh');
        session()->flash('success_profile', app()->getLocale() === 'id'
            ? 'Profil diperbarui!'
            : 'Profile updated!');
    }

    public function updatePreferences()
    {
        $this->validate([
            'language' => 'required|in:id,en',
        ]);

        $user = auth()->user();
        $user->language = $this->language;
        $user->sound_enabled = $this->soundEnabled;
        $user->save();

        session(['locale' => $this->language]);

        session()->flash('success_prefs', app()->getLocale() === 'id'
            ? 'Preferensi disimpan!'
            : 'Preferences saved!');
    }

    public function updatePassword()
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            abort(403);
        }

        $this->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', app()->getLocale() === 'id'
                ? 'Kata sandi saat ini salah.'
                : 'Current password is incorrect.');
            return;
        }

        $user->password = Hash::make($this->newPassword);
        $user->save();

        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);

        session()->flash('success_security', app()->getLocale() === 'id'
            ? 'Kata sandi berhasil diubah!'
            : 'Password changed successfully!');
    }

    public function render()
    {
        return view('livewire.settings.page');
    }
}