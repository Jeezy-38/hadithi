<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Watumiaji wa social login hawana password, na X inaweza kukosa email.
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->text('avatar')->nullable()->after('email');
        });

        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 32);
            $table->string('provider_id');
            $table->string('provider_email')->nullable();
            $table->text('avatar')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
