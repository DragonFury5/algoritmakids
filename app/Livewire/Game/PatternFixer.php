<?php

namespace App\Livewire\Game;

use App\Models\Level;
use App\Models\Progress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PatternFixer extends Component
{
    public Level $level;
    public int $previousStars = 0;

    public function mount(Level $level)
    {
        if ($level->game_type !== 'pattern_fixer') {
            abort(404);
        }

        $this->level = $level;

        $progress = Progress::where('user_id', auth()->id())
            ->where('level_id', $level->id)
            ->first();

        $this->previousStars = $progress?->stars ?? 0;
    }

    public function completeLevel(int $stars)
    {
        $stars = max(1, min(3, $stars));

        $progress = Progress::firstOrNew([
            'user_id' => auth()->id(),
            'level_id' => $this->level->id,
        ]);

        $progress->attempts = ($progress->attempts ?? 0) + 1;

        if ($stars > ($progress->stars ?? 0)) {
            $progress->stars = $stars;
        }

        $progress->completed_at = now();
        $progress->save();

        $this->previousStars = $progress->stars;
    }

    public function render()
    {
        $nextLevel = Level::where('module_id', $this->level->module_id)
            ->where('order', '>', $this->level->order)
            ->orderBy('order')
            ->first();

        return view('livewire.game.pattern-fixer', [
            'nextLevel' => $nextLevel,
        ]);
    }
}