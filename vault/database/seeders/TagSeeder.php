<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $starter = [
            ['name' => 'comfyui',    'color' => '#00e5ff'],
            ['name' => 'render',     'color' => '#ffe066'],
            ['name' => 'reference',  'color' => '#a78bfa'],
            ['name' => 'screenshot', 'color' => '#34d399'],
            ['name' => 'personal',   'color' => '#ff3d71'],
        ];

        foreach ($starter as $t) {
            Tag::firstOrCreate(['name' => $t['name']], ['color' => $t['color']]);
        }
    }
}
