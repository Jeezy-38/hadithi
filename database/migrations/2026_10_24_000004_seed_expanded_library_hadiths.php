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

        // Husasisha hadithi zote 7,500 za maktaba kuu
        // (Bukhari, Muslim, Abu Dawud, Tirmidhi, Riyadh as-Salihin, Ahmad, Ibn Majah, Nasa'i na Nawawi 40)
        // moja kwa moja wakati wa php artisan migrate --force kwenye seva ya uzalishaji.
        (new HadithSeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
