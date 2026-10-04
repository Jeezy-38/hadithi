<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quran_surahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('number')->unique();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('name_sw');
            $table->string('translation_sw');
            $table->enum('revelation_type', ['Makki', 'Madani'])->default('Makki');
            $table->unsignedSmallInteger('total_verses');
            $table->unsignedTinyInteger('juz_start')->default(1);
            $table->unsignedSmallInteger('page_start')->default(1);
            $table->boolean('bismillah_pre')->default(true);
            $table->timestamps();

            $table->index(['number', 'revelation_type']);
        });

        Schema::create('quran_ayahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('surah_number');
            $table->unsignedSmallInteger('verse_number');
            $table->unsignedTinyInteger('juz_number')->default(1);
            $table->unsignedSmallInteger('page_number')->default(1);
            $table->text('arabic_text');
            $table->text('arabic_clean')->nullable();
            $table->text('translation_sw');
            $table->text('translation_en')->nullable();
            $table->string('audio_url')->nullable();
            $table->timestamps();

            $table->foreign('surah_number')->references('number')->on('quran_surahs')->cascadeOnDelete();
            $table->unique(['surah_number', 'verse_number']);
            $table->index(['surah_number', 'juz_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quran_ayahs');
        Schema::dropIfExists('quran_surahs');
    }
};
