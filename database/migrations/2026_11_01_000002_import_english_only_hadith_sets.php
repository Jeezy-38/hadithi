<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /** Ingiza hadith za Bukhari, Muslim na Abu Dawud zilizobaki (Kiarabu + Kiingereza tu). Inaweza kurudiwa bila madhara. */
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }
        @ini_set('memory_limit', '1024M');
        @set_time_limit(0);
        foreach (['bukhari', 'muslim', 'abudawud'] as $slug) {
            $file = database_path("data/hadith-extra-{$slug}.json");
            if (file_exists($file)) {
                Artisan::call('hadith:import', ['file' => $file]);
            }
        }
    }

    public function down(): void
    {
        // Hakuna kurudisha nyuma: data inaweza kufutwa kwa reference "*:fawazahmed0:*".
    }
};
