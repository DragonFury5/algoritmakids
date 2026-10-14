<?php

namespace App\Livewire\Admin\Modules;

use App\Models\Module;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $slug = '';
    public string $title_en = '';
    public string $title_id = '';
    public string $description_en = '';
    public string $description_id = '';
    public int $order = 0;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'slug' => 'required|string|max:60|regex:/^[a-z0-9\-]+$/|unique:modules,slug,' . ($this->editingId ?? 'NULL'),
            'title_en' => 'required|string|max:80',
            'title_id' => 'required|string|max:80',
            'description_en' => 'required|string|max:255',
            'description_id' => 'required|string|max:255',
            'order' => 'required|integer|min:0|max:999',
        ];
    }

    protected function messages(): array
    {
        return [
            'slug.regex' => 'Slug may only contain lowercase letters, numbers, and hyphens.',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'slug', 'title_en', 'title_id', 'description_en', 'description_id', 'order']);
        $this->order = (Module::max('order') ?? 0) + 1;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $module = Module::findOrFail($id);
        $this->editingId = $module->id;
        $this->slug = $module->slug;
        $this->title_en = $module->title_en;
        $this->title_id = $module->title_id;
        $this->description_en = $module->description_en;
        $this->description_id = $module->description_id;
        $this->order = $module->order;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            Module::findOrFail($this->editingId)->update([
                'slug' => $this->slug,
                'title_en' => $this->title_en,
                'title_id' => $this->title_id,
                'description_en' => $this->description_en,
                'description_id' => $this->description_id,
                'order' => $this->order,
            ]);
            session()->flash('success', 'Module updated.');
        } else {
            Module::create([
                'slug' => $this->slug,
                'title_en' => $this->title_en,
                'title_id' => $this->title_id,
                'description_en' => $this->description_en,
                'description_id' => $this->description_id,
                'order' => $this->order,
            ]);
            session()->flash('success', 'Module created.');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if (! $this->deletingId) return;

        $module = Module::withCount('levels')->findOrFail($this->deletingId);
        $count = $module->levels_count;

        $module->delete();

        session()->flash('success', "Module deleted" . ($count > 0 ? " along with {$count} level(s)." : '.'));
        $this->deletingId = null;
    }

    public function render()
    {
        $modules = Module::withCount('levels')->orderBy('order')->get();

        return view('livewire.admin.modules.index', [
            'modules' => $modules,
        ]);
    }
}