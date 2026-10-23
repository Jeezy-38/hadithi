<?php

use Database\Seeders\HadithSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        // Husasisha orodha ya hadithi zote 2,000 kutoka database/data/hadith-starter.json
        // moja kwa moja wakati wa php artisan migrate --force kwenye seva ya uzalishaji.
        (new HadithSeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
