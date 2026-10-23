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

        // Husasisha hadithi zote 3,938 (ikijumuisha hadithi zote 1,896 za Riyadh as-Salihin)
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
