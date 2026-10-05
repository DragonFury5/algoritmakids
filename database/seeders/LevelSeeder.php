<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Module;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $seq = Module::where('slug', 'sequencing')->first();
        if (! $seq) return;

        $levels = [
            [
                'slug' => 'straight-line',
                'title_en' => 'Straight Line',
                'title_id' => 'Garis Lurus',
                'instructions_en' => 'Move the robot forward to reach the flag.',
                'instructions_id' => 'Gerakkan robot maju untuk mencapai bendera.',
                'order' => 1,
                'game_data' => [
                    'width' => 5, 'height' => 3,
                    'start' => ['x' => 0, 'y' => 1, 'dir' => 'right'],
                    'goal'  => ['x' => 4, 'y' => 1],
                    'walls' => [],
                    'max_blocks' => 8,
                ],
            ],
            [
                'slug' => 'turn-corner',
                'title_en' => 'Turn the Corner',
                'title_id' => 'Belok di Sudut',
                'instructions_en' => 'Turn and move to reach the flag.',
                'instructions_id' => 'Belok dan bergerak untuk mencapai bendera.',
                'order' => 2,
                'game_data' => [
                    'width' => 5, 'height' => 5,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 4, 'y' => 4],
                    'walls' => [],
                    'max_blocks' => 12,
                ],
            ],
            [
                'slug' => 'around-wall',
                'title_en' => 'Around the Wall',
                'title_id' => 'Melewati Dinding',
                'instructions_en' => 'Navigate around the wall to reach the flag.',
                'instructions_id' => 'Hindari dinding untuk mencapai bendera.',
                'order' => 3,
                'game_data' => [
                    'width' => 5, 'height' => 5,
                    'start' => ['x' => 0, 'y' => 2, 'dir' => 'right'],
                    'goal'  => ['x' => 4, 'y' => 2],
                    'walls' => [[2, 1], [2, 2], [2, 3]],
                    'max_blocks' => 15,
                ],
            ],
        ];

        foreach ($levels as $data) {
            Level::updateOrCreate(
                ['module_id' => $seq->id, 'slug' => $data['slug']],
                array_merge($data, [
                    'module_id' => $seq->id,
                    'game_type' => 'robot_path',
                ])
            );
        }
    }
}