<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('endpoint', 500)->unique();
            $table->text('public_key')->nullable();
            $table->text('auth_token')->nullable();
            $table->string('content_encoding', 32)->default('aes128gcm');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
