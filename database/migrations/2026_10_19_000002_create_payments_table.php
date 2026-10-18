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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reference')->unique()->index();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable()->index();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('TZS')->index();
            $table->string('purpose')->default('sadaka_jariya')->index();
            $table->string('tier')->nullable();
            $table->string('billing_cycle')->default('one_time');
            $table->string('payment_method')->nullable();
            $table->string('gateway')->default('pesapal')->index();
            $table->string('gateway_reference')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
