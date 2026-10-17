<?php

namespace App\Livewire\Admin\Levels;

use App\Models\Level;
use App\Models\Module;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public ?int $filterModule = null;
    public ?string $filterTier = null;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $slug = '';
    public string $title_en = '';
    public string $title_id = '';
    public string $instructions_en = '';
    public string $instructions_id = '';
    public ?int $module_id = null;
    public string $tier = 'rookie';
    public string $game_type = 'robot_path';
    public int $order = 0;
    public string $game_data_json = '';

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'module_id'        => 'required|exists:modules,id',
            'tier'             => 'required|in:rookie,explorer,pro,hacker',
            'game_type'        => 'required|in:robot_path,pattern_fixer',
            'slug'             => 'required|string|max:60|regex:/^[a-z0-9\-]+$/',
            'title_en'         => 'required|string|max:80',
            'title_id'         => 'required|string|max:80',
            'instructions_en'  => 'required|string|max:500',
            'instructions_id'  => 'required|string|max:500',
            'order'            => 'required|integer|min:0|max:999',
            'game_data_json'   => 'required|string',
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
        $this->reset([
            'editingId', 'slug', 'title_en', 'title_id',
            'instructions_en', 'instructions_id', 'module_id',
            'game_data_json',
        ]);
        $this->tier = 'rookie';
        $this->game_type = 'robot_path';
        $this->order = (Level::max('order') ?? 0) + 1;
        $this->game_data_json = $this->template('robot_path');
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $lvl = Level::findOrFail($id);
        $this->editingId       = $lvl->id;
        $this->slug            = $lvl->slug;
        $this->title_en        = $lvl->title_en;
        $this->title_id        = $lvl->title_id;
        $this->instructions_en = $lvl->instructions_en;
        $this->instructions_id = $lvl->instructions_id;
        $this->module_id       = $lvl->module_id;
        $this->tier            = $lvl->tier;
        $this->game_type       = $lvl->game_type;
        $this->order           = $lvl->order;
        $this->game_data_json  = json_encode($lvl->game_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->showModal       = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function updatedGameType(): void
    {
        if (! $this->editingId) {
            $this->game_data_json = $this->template($this->game_type);
        }
    }

    public function loadTemplate(string $type): void
    {
        $this->game_data_json = $this->template($type);
    }

    protected function template(string $type): string
    {
        if ($type === 'pattern_fixer') {
            return json_encode([
                'width' => 5, 'height' => 3,
                'start' => ['x' => 0, 'y' => 1, 'dir' => 'right'],
                'goal'  => ['x' => 4, 'y' => 1],
                'walls' => [],
                'program' => [
                    ['type' => 'forward'],
                    ['type' => 'forward'],
                    ['type' => 'left'],
                    ['type' => 'forward'],
                    ['type' => 'forward'],
                ],
                'bug_index' => 2,
                'options' => ['forward', 'left', 'right'],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

      return json_encode([
    'width' => 6, 'height' => 6,
    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
    'goal'  => ['x' => 5, 'y' => 5],
    'walls' => [[2,0],[2,1],[2,2],[3,3],[4,3]],
    'max_blocks' => 20,
    'allow_loops' => true,
    'allow_while_loops' => true, // Renamed from allow_conditionals
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function save(): void
    {
        $this->validate();

        $decoded = json_decode($this->game_data_json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addError('game_data_json', 'Invalid JSON: ' . json_last_error_msg());
            return;
        }

        $duplicate = Level::where('module_id', $this->module_id)
            ->where('slug', $this->slug)
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('slug', 'This slug already exists in this module.');
            return;
        }

        $data = [
            'module_id'       => $this->module_id,
            'tier'            => $this->tier,
            'game_type'       => $this->game_type,
            'slug'            => $this->slug,
            'title_en'        => $this->title_en,
            'title_id'        => $this->title_id,
            'instructions_en' => $this->instructions_en,
            'instructions_id' => $this->instructions_id,
            'order'           => $this->order,
            'game_data'       => $decoded,
        ];

        if ($this->editingId) {
            Level::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Level updated.');
        } else {
            Level::create($data);
            session()->flash('success', 'Level created.');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
        $this->showDeleteModal = false;
    }

    public function delete(): void
    {
        if (! $this->deletingId) return;

        $lvl = Level::withCount('progress')->findOrFail($this->deletingId);
        $progressCount = $lvl->progress_count;

        $lvl->delete();

        session()->flash('success', 'Level deleted' . ($progressCount > 0 ? " along with {$progressCount} progress record(s)." : '.'));
        $this->cancelDelete();
    }

    public function clearFilters(): void
    {
        $this->filterModule = null;
        $this->filterTier = null;
    }

    public function render()
    {
        $query = Level::with('module')->orderBy('module_id')->orderBy('order');

        if ($this->filterModule) {
            $query->where('module_id', $this->filterModule);
        }
        if ($this->filterTier) {
            $query->where('tier', $this->filterTier);
        }

        return view('livewire.admin.levels.index', [
            'levels'   => $query->get(),
            'modules'  => Module::orderBy('order')->get(),
            'tiers'    => config('tiers'),
        ]);
    }
}