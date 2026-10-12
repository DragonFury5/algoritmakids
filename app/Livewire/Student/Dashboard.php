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
    $tier = $user->tier;

    $modules = \App\Models\Module::with(['levels' => function ($q) use ($tier) {
        $q->where('tier', $tier)->orderBy('order');
    }])
    ->orderBy('order')
    ->get()
    ->filter(fn ($m) => $m->levels->count() > 0);

    $totalStars = $user->progress()->sum('stars');
    $badgeCount = $user->badges()->count();

    return view('livewire.student.dashboard', [
        'modules' => $modules,
        'totalStars' => $totalStars,
        'badgeCount' => $badgeCount,
        'tier' => $tier,
    ]);
}
}