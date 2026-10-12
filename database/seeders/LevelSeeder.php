<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Module;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSequencing();
        $this->seedLoops();
        $this->seedConditionals();
        $this->seedExplorerSequencing();
    }

    protected function seedSequencing(): void
    {
        $seq = Module::where('slug', 'sequencing')->first();
        if (! $seq) return;

        $levels = [
            [
                'tier' => 'rookie',
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
                'tier' => 'rookie',
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
                'tier' => 'rookie',
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

        $this->upsert($levels, $seq, 'robot_path');
    }

    protected function seedLoops(): void
    {
        $loops = Module::where('slug', 'loops')->first();
        if (! $loops) return;

        $levels = [
            [
                'tier' => 'rookie',
                'slug' => 'repeat-straight',
                'title_en' => 'Repeat and Walk',
                'title_id' => 'Ulangi dan Jalan',
                'instructions_en' => 'Use a loop to move forward 6 times.',
                'instructions_id' => 'Gunakan loop untuk maju 6 kali.',
                'order' => 1,
                'game_data' => [
                    'width' => 7, 'height' => 3,
                    'start' => ['x' => 0, 'y' => 1, 'dir' => 'right'],
                    'goal'  => ['x' => 6, 'y' => 1],
                    'walls' => [],
                    'max_blocks' => 4,
                    'allow_loops' => true,
                ],
            ],
            [
                'tier' => 'rookie',
                'slug' => 'repeat-square',
                'title_en' => 'Square Path',
                'title_id' => 'Jalur Persegi',
                'instructions_en' => 'Use a loop to go around the square.',
                'instructions_id' => 'Gunakan loop untuk mengelilingi persegi.',
                'order' => 2,
                'game_data' => [
                    'width' => 5, 'height' => 5,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 0, 'y' => 0],
                    'walls' => [],
                    'max_blocks' => 6,
                    'allow_loops' => true,
                ],
            ],
            [
                'tier' => 'rookie',
                'slug' => 'repeat-zigzag',
                'title_en' => 'Zigzag',
                'title_id' => 'Zig-zag',
                'instructions_en' => 'Repeat the pattern to reach the flag.',
                'instructions_id' => 'Ulangi pola untuk mencapai bendera.',
                'order' => 3,
                'game_data' => [
                    'width' => 7, 'height' => 5,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 6, 'y' => 4],
                    'walls' => [],
                    'max_blocks' => 8,
                    'allow_loops' => true,
                ],
            ],
        ];

        $this->upsert($levels, $loops, 'robot_path');
    }

    protected function seedConditionals(): void
    {
        $cond = Module::where('slug', 'conditionals')->first();
        if (! $cond) return;

        $levels = [
            [
                'tier' => 'rookie',
                'slug' => 'straight-fix',
                'title_en' => 'Fix the Straight Path',
                'title_id' => 'Perbaiki Jalur Lurus',
                'instructions_en' => 'One block is wrong. Click it and pick the correct one so the robot reaches the flag.',
                'instructions_id' => 'Satu blok salah. Klik dan pilih yang benar agar robot mencapai bendera.',
                'order' => 1,
                'game_data' => [
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
                ],
            ],
            [
                'tier' => 'rookie',
                'slug' => 'turn-down',
                'title_en' => 'Turn Down',
                'title_id' => 'Belok ke Bawah',
                'instructions_en' => 'The robot needs to turn down to reach the flag. Fix the wrong block.',
                'instructions_id' => 'Robot harus belok ke bawah untuk mencapai bendera. Perbaiki blok yang salah.',
                'order' => 2,
                'game_data' => [
                    'width' => 3, 'height' => 5,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 2, 'y' => 4],
                    'walls' => [],
                    'program' => [
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                        ['type' => 'left'],
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                    ],
                    'bug_index' => 2,
                    'options' => ['forward', 'left', 'right'],
                ],
            ],
            [
                'tier' => 'rookie',
                'slug' => 'down-to-flag',
                'title_en' => 'Down to the Flag',
                'title_id' => 'Turun ke Bendera',
                'instructions_en' => 'Fix the last block so the robot reaches the flag.',
                'instructions_id' => 'Perbaiki blok terakhir agar robot mencapai bendera.',
                'order' => 3,
                'game_data' => [
                    'width' => 4, 'height' => 4,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 3, 'y' => 3],
                    'walls' => [],
                    'program' => [
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                        ['type' => 'right'],
                        ['type' => 'forward'],
                        ['type' => 'forward'],
                        ['type' => 'left'],
                    ],
                    'bug_index' => 6,
                    'options' => ['forward', 'left', 'right'],
                ],
            ],
        ];

        $this->upsert($levels, $cond, 'pattern_fixer');
    }

    protected function seedExplorerSequencing(): void
    {
        $seq = Module::where('slug', 'sequencing')->first();
        if (! $seq) return;

        $levels = [
            [
                'tier' => 'explorer',
                'slug' => 'maze-turn',
                'title_en' => 'Maze Turn',
                'title_id' => 'Belokan Labirin',
                'instructions_en' => 'Navigate around the walls to reach the flag.',
                'instructions_id' => 'Hindari dinding untuk mencapai bendera.',
                'order' => 10,
                'game_data' => [
                    'width' => 6, 'height' => 6,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 5, 'y' => 5],
                    'walls' => [[2,0],[2,1],[2,2],[3,3],[4,3]],
                    'max_blocks' => 15,
                ],
            ],
            [
                'tier' => 'explorer',
                'slug' => 'long-path',
                'title_en' => 'Long Path',
                'title_id' => 'Jalur Panjang',
                'instructions_en' => 'Plan carefully to reach the flag.',
                'instructions_id' => 'Rencanakan dengan hati-hati untuk mencapai bendera.',
                'order' => 11,
                'game_data' => [
                    'width' => 8, 'height' => 5,
                    'start' => ['x' => 0, 'y' => 0, 'dir' => 'right'],
                    'goal'  => ['x' => 7, 'y' => 4],
                    'walls' => [[2,1],[2,2],[2,3],[5,1],[5,2],[5,3]],
                    'max_blocks' => 18,
                ],
            ],
            [
                'tier' => 'explorer',
                'slug' => 'tight-corner',
                'title_en' => 'Tight Corner',
                'title_id' => 'Sudut Sempit',
                'instructions_en' => 'Careful — the path is narrow.',
                'instructions_id' => 'Hati-hati — jalannya sempit.',
                'order' => 12,
                'game_data' => [
                    'width' => 6, 'height' => 6,
                    'start' => ['x' => 0, 'y' => 2, 'dir' => 'right'],
                    'goal'  => ['x' => 5, 'y' => 3],
                    'walls' => [[1,0],[1,1],[1,3],[1,4],[2,2],[3,2],[4,2]],
                    'max_blocks' => 20,
                ],
            ],
        ];

        $this->upsert($levels, $seq, 'robot_path');
    }

    protected function upsert(array $levels, Module $module, string $gameType): void
    {
        foreach ($levels as $data) {
            Level::updateOrCreate(
                ['module_id' => $module->id, 'slug' => $data['slug']],
                array_merge($data, [
                    'module_id' => $module->id,
                    'game_type' => $gameType,
                ])
            );
        }
    }
}