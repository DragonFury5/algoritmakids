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

        // Load modules that have at least one level for this tier
        $modules = Module::with(['levels' => function ($q) use ($tier) {
            $q->where('tier', $tier)->orderBy('order');
        }])
        ->orderBy('order')
        ->get()
        ->filter(fn ($m) => $m->levels->count() > 0);

        // Load all progress for this user, keyed by level_id
        $progressByLevel = $user->progress()->get()->keyBy('level_id');

        // Annotate each module with progress + style hints
        $modules = $modules->map(function ($module) use ($progressByLevel) {
            $levels = $module->levels;
            $totalStars = $levels->count() * 3;

            $earned = $levels->sum(function ($level) use ($progressByLevel) {
                return $progressByLevel->get($level->id)?->stars ?? 0;
            });

            $completedLevels = $levels->filter(function ($level) use ($progressByLevel) {
                return $progressByLevel->has($level->id);
            })->count();

            // Deterministic style per module slug
            $colors = ['kid-blue', 'kid-mint', 'kid-purple', 'kid-yellow'];
            $icons  = ['target', 'repeat', 'git-branch', 'blocks', 'puzzle', 'lightbulb'];

            $module->style_color = $colors[crc32($module->slug) % count($colors)];
            $module->style_icon  = $icons[crc32($module->slug) % count($icons)];

            $module->progress = [
                'earned'           => $earned,
                'total'            => $totalStars,
                'completed_levels' => $completedLevels,
                'total_levels'     => $levels->count(),
                'percent'          => $totalStars > 0 ? round(($earned / $totalStars) * 100) : 0,
                'started'          => $earned > 0 || $completedLevels > 0,
                'finished'         => $totalStars > 0 && $earned >= $totalStars,
            ];

            return $module;
        });

        return view('livewire.student.dashboard', [
            'modules'    => $modules,
            'totalStars' => $user->progress()->sum('stars'),
            'badgeCount' => $user->badges()->count(),
            'tier'       => $tier,
        ]);
    }
}