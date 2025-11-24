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
    $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
    $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();

    $table->decimal('amount', 12, 2);
    $table->string('currency', 8)->default('LYD');
    $table->enum('status', ['initiated','succeeded','failed','refunded'])->default('initiated');

    $table->string('provider')->nullable();     // stripe/paypal/محلي
    $table->string('provider_payment_id')->nullable();
    $table->string('provider_payer_id')->nullable();
    $table->json('provider_payload')->nullable();

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
