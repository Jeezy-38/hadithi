<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class HadithSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/hadith-starter.json');
        if (file_exists($path)) {
            Artisan::call('hadith:import', ['file' => $path]);
        }
    }
}
