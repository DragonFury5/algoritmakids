<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Module;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $iconMap = [
            'sequencing'   => 'list-ordered',
            'loops'        => 'repeat',
            'conditionals' => 'git-branch',
        ];

        foreach (Module::all() as $module) {
            $icon = $iconMap[$module->slug] ?? 'award';

            Badge::updateOrCreate(
                ['slug' => $module->slug . '-master'],
                [
                    'module_id' => $module->id,
                    'name_en'   => $module->title_en . ' Master',
                    'name_id'   => 'Master ' . $module->title_id,
                    'icon'      => $icon,
                ]
            );
        }
    }
}