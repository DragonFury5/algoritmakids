<?php

namespace App\Livewire\Game;

use App\Models\Level;
use App\Models\Progress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RobotPath extends Component
{
    public Level $level;
    public int $previousStars = 0;

    public function mount(Level $level)
    {
        if ($level->game_type !== 'robot_path') {
            abort(404);
        }

        $this->level = $level;
        
                if (! $level->isUnlockedFor(auth()->user())) {
            session()->flash('error', app()->getLocale() === 'id' ? 'Selesaikan level sebelumnya dulu!' : 'Complete the previous level first!');
            $this->redirectRoute('student.module', ['module' => $level->module_id]);
            return;
        }

        $progress = Progress::where('user_id', auth()->id())
            ->where('level_id', $level->id)
            ->first();

        $this->previousStars = $progress?->stars ?? 0;
    }

        public function completeLevel(int $stars)
    {
        $stars = max(1, min(3, $stars));

        // updateOrCreate is safer than firstOrNew here
        $progress = Progress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'level_id' => $this->level->id,
            ],
            [
                'stars' => $stars,
                'completed_at' => now(),
            ]
        );

        // Increment attempts separately to avoid race conditions
        $progress->increment('attempts');

        $this->previousStars = $progress->stars;
        auth()->user()->checkModuleCompletion($this->level->module_id);
    }

    public function render()
    {
        $nextLevel = Level::where('module_id', $this->level->module_id)
            ->where('tier', $this->level->tier)
            ->where('order', '>', $this->level->order)
            ->orderBy('order')
            ->first();

        return view('livewire.game.robot-path', [
            'nextLevel' => $nextLevel,
        ]);
    }
}