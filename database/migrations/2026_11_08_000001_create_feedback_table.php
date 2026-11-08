<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category', 40)->default('general')->index(); // feature, correction, bug, general
            $table->string('name')->nullable();
            $table->string('contact')->nullable(); // Email or WhatsApp
            $table->text('message');
            $table->string('page_url')->nullable();
            $table->string('hadith_ref')->nullable();
            $table->string('status', 30)->default('new')->index(); // new, read, resolved
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
