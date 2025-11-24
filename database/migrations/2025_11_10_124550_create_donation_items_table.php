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
        Schema::create('donation_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
    $table->string('category'); // ملابس، أثاث، غذاء، أجهزة، ...
    $table->string('condition')->nullable(); // جديد، جيد جدًا، جيد...
    $table->string('quantity')->nullable();  // نص حر (مثلا "3 قطع" أو "10 كغ")
    $table->text('notes')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_items');
    }
};
