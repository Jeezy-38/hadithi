<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Masomo (hadith) ambayo mtumiaji ameyakamilisha.
        Schema::create('learning_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hadith_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();
            $table->unique(['user_id', 'hadith_id']);
        });

        // Kadi za kuhifadhi (kurudia kwa nafasi).
        Schema::create('memorization_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hadith_id')->constrained()->cascadeOnDelete();
            $table->float('ease')->default(2.5);
            $table->unsignedInteger('interval_days')->default(0);
            $table->unsignedInteger('repetitions')->default(0);
            $table->timestamp('due_at')->nullable()->index();
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'hadith_id']);
        });

        // Siku ambazo mtumiaji alijifunza (kwa streak).
        Schema::create('learning_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('day');
            $table->timestamps();
            $table->unique(['user_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_days');
        Schema::dropIfExists('memorization_cards');
        Schema::dropIfExists('learning_progress');
    }
};
