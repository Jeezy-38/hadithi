<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hadiths', function (Blueprint $table) {
            $table->text('title')->nullable();
            $table->string('source_record_id')->nullable();
            $table->text('reference_url')->nullable();
            $table->text('attribution')->nullable();
            $table->string('grade')->nullable();
            $table->text('content_note')->nullable();
            $table->text('license_url')->nullable();
            $table->timestamp('source_fetched_at')->nullable();
            $table->string('source_sha256', 64)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('hadiths', function (Blueprint $table) {
            $table->dropColumn(['title', 'source_record_id', 'reference_url', 'attribution', 'grade', 'content_note', 'license_url', 'source_fetched_at', 'source_sha256']);
        });
    }
};
