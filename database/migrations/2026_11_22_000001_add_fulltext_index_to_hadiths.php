<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('hadiths', function (Blueprint $table) {
            $table->fullText(['search_arabic', 'search_swahili', 'english'], 'hadiths_search_fulltext');
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('hadiths', function (Blueprint $table) {
            $table->dropFullText('hadiths_search_fulltext');
        });
    }
};
