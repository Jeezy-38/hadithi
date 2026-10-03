<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dua_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_sw');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('dua_categories')->cascadeOnDelete();
            $table->string('title_sw');
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->text('arabic');
            $table->text('transliteration')->nullable();
            $table->text('swahili');
            $table->text('english')->nullable();
            $table->string('reference')->nullable();
            $table->text('virtue_sw')->nullable();
            $table->text('virtue_en')->nullable();
            $table->integer('target_count')->default(1);
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duas');
        Schema::dropIfExists('dua_categories');
    }
};
