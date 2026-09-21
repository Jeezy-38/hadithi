<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_ar');
            $table->timestamps();
        });
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->string('title_sw');
            $table->string('title_ar');
            $table->timestamps();
            $table->unique(['collection_id', 'number']);
        });
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->text('title_sw');
            $table->text('title_ar');
            $table->timestamps();
            $table->unique(['book_id', 'number']);
        });
        Schema::create('hadiths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('number')->index();
            $table->text('arabic');
            $table->text('swahili');
            $table->text('search_arabic');
            $table->text('search_swahili');
            $table->string('source_name');
            $table->text('source_url');
            $table->string('numbering_system');
            $table->string('translator');
            $table->text('translation_source_url');
            $table->string('license');
            $table->string('reviewed_by');
            $table->date('reviewed_at');
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hadiths');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('books');
        Schema::dropIfExists('collections');
    }
};
