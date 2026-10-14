<?php

namespace App\Livewire\Parent;

use App\Models\Level;
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
            ->get()
            ->map(function ($child) {
                // Stars earned by this child
                $starsEarned = $child->progress()->sum('stars');

                // Levels completed
                $levelsCompleted = $child->progress()->count();

                // Levels available for this child's tier
                $levelsAvailable = Level::where('tier', $child->tier)->count();
                $maxStars = $levelsAvailable * 3;

                $child->stats = [
                    'stars'             => $starsEarned,
                    'max_stars'         => $maxStars,
                    'levels_completed'  => $levelsCompleted,
                    'levels_available'  => $levelsAvailable,
                    'percent'           => $maxStars > 0 ? round(($starsEarned / $maxStars) * 100) : 0,
                ];

                return $child;
            });

        return view('livewire.parent.dashboard', [
            'children' => $children,
        ]);
    }
}