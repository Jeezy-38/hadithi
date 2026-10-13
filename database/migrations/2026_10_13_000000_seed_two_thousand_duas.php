<?php

use Database\Seeders\DuaSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Husasisha orodha ya dua zote 2,000 kutoka database/data/duas-starter.json
        // moja kwa moja wakati wa php artisan migrate --force kwenye seva (Laravel Cloud).
        (new DuaSeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
