<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('country', 2)->nullable()->after('device_type')->index();
            $table->string('browser', 24)->nullable()->after('country');
            $table->string('os', 24)->nullable()->after('browser');
            $table->string('referer_host', 120)->nullable()->after('referer')->index();
        });

        // Jaza referer_host kwa rekodi za zamani (host tu, hakuna data binafsi)
        DB::table('page_views')->whereNotNull('referer')->orderBy('id')->chunkById(1000, function ($rows) {
            foreach ($rows as $row) {
                $host = parse_url((string) $row->referer, PHP_URL_HOST);
                if ($host) {
                    DB::table('page_views')->where('id', $row->id)->update(['referer_host' => mb_substr(strtolower($host), 0, 120)]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn(['country', 'browser', 'os', 'referer_host']);
        });
    }
};
