<?php

namespace App\Livewire\Teacher;

use App\Models\Classroom;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClassroomView extends Component
{
    public Classroom $classroom;

    public function mount(Classroom $classroom)
    {
        if ($classroom->teacher_id !== auth()->id()) {
            abort(403);
        }
        $this->classroom = $classroom;
    }

    public function render()
    {
        // Leaderboard Logic: Eager load progress to calculate total stars
        $students = $this->classroom->students()
            ->withSum('progress', 'stars')
            ->get()
            ->map(function ($student) {
                $student->total_stars = $student->progress_sum_stars ?? 0;
                return $student;
            })
            ->sortByDesc('total_stars')
            ->values(); // Re-index

        return view('livewire.teacher.classroom-view', compact('students'));
    }
}