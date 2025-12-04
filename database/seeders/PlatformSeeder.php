<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::insert([
            ['name' => 'Laravel', 'color_class' => 'bg-red-100 text-red-800 border-red-200'],
            ['name' => 'Vue.js',  'color_class' => 'bg-green-100 text-green-800 border-green-200'],
            ['name' => 'Docker',  'color_class' => 'bg-blue-100 text-blue-800 border-blue-200'],
            ['name' => 'Git',     'color_class' => 'bg-orange-100 text-orange-800 border-orange-200'],
            ['name' => 'Outros',  'color_class' => 'bg-gray-100 text-gray-800 border-gray-200'],
        ]);
    }
}
