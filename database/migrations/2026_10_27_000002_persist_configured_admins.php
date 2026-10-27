<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Admin walioorodheshwa kwenye .env (ADMIN_EMAIL / ADMIN_EMAILS) wanahifadhiwa kwenye
 * database (is_admin = true) ili wasifungiwe nje baada ya kudai email iliyothibitishwa.
 */
return new class extends Migration
{
    public function up(): void
    {
        $emails = collect([config('app.admin_email')])
            ->merge(explode(',', (string) config('app.admin_emails', '')))
            ->map(fn ($e) => strtolower(trim((string) $e)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($emails !== []) {
            DB::table('users')->whereIn(DB::raw('lower(email)'), $emails)->update(['is_admin' => true]);
        }
    }

    public function down(): void
    {
        // Hakuna cha kurudisha kwa usalama.
    }
};
