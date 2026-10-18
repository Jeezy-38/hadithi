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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_supporter')->default(false)->after('avatar')->index();
            $table->string('supporter_tier')->nullable()->after('is_supporter');
            $table->timestamp('supporter_since')->nullable()->after('supporter_tier');
            $table->timestamp('supporter_expires_at')->nullable()->after('supporter_since');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_supporter']);
            $table->dropColumn([
                'is_supporter',
                'supporter_tier',
                'supporter_since',
                'supporter_expires_at',
            ]);
        });
    }
};
