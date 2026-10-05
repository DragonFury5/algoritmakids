<?php

namespace App\Livewire\Parent;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    protected $listeners = ['child-added' => '$refresh'];

    public function render()
    {
        $children = auth()->user()
            ->children()
            ->withCount('progress')
            ->get();

        return view('livewire.parent.dashboard', [
            'children' => $children,
        ]);
    }
}