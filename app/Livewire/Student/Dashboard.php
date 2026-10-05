<?php

namespace App\Livewire\Student;

use App\Models\Module;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $modules = Module::with('levels')->orderBy('order')->get();

        // Total stars for the header
        $totalStars = $user->progress()->sum('stars');

        // Badge count
        $badgeCount = $user->badges()->count();

        return view('livewire.student.dashboard', [
            'modules' => $modules,
            'totalStars' => $totalStars,
            'badgeCount' => $badgeCount,
        ]);
    }
}