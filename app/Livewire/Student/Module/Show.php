<?php

namespace App\Livewire\Student\Module;

use App\Models\Module;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Module $module;

    public function mount(Module $module)
    {
        $this->module = $module;
    }

    public function render()
    {
        $user = auth()->user();

        // Load levels for this module and the user's tier
        $levels = $this->module->levels()
            ->where('tier', $user->tier)
            ->orderBy('order')
            ->get();

        // Load progress
        $progressByLevel = $user->progress()
            ->whereIn('level_id', $levels->pluck('id'))
            ->get()
            ->keyBy('level_id');

        return view('livewire.student.module.show', [
            'levels' => $levels,
            'progressByLevel' => $progressByLevel,
        ]);
    }
}