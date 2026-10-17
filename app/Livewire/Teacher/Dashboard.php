<?php

namespace App\Livewire\Teacher;

use App\Models\Classroom;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public string $newClassName = '';
    public bool $showCreateModal = false;

    public function createClassroom()
    {
        $this->validate(['newClassName' => 'required|string|max:60']);
        
        Classroom::create([
            'teacher_id' => auth()->id(),
            'name' => $this->newClassName,
        ]);

        $this->reset(['newClassName', 'showCreateModal']);
        session()->flash('success', 'Class created successfully!');
    }

    public function render()
    {
        $classrooms = auth()->user()->classroomsOwned()->withCount('students')->latest()->get();
        return view('livewire.teacher.dashboard', compact('classrooms'));
    }
}