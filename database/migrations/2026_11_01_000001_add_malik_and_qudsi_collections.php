<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            ['slug' => 'malik', 'name' => 'Muwatta Malik', 'name_ar' => 'موطأ الإمام مالك'],
            ['slug' => 'qudsi', 'name' => 'Hadithi 40 za Qudsi', 'name_ar' => 'الأحاديث القدسية'],
        ] as $row) {
            $exists = DB::table('collections')->where('slug', $row['slug'])->exists();
            if (! $exists) {
                DB::table('collections')->insert($row + ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        // Ingiza hadith za Kiarabu + Kiingereza (hakuna Kiswahili). Inaweza kurudiwa bila madhara.
        $file = database_path('data/hadith-extra.json');
        if (file_exists($file) && ! app()->environment('testing')) {
            Artisan::call('hadith:import', ['file' => $file]);
        }
    }

    public function down(): void
    {
        DB::table('collections')->whereIn('slug', ['malik', 'qudsi'])->delete();
    }
};
