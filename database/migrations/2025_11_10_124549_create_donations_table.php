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
        Schema::create('donations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();

    $table->enum('type', ['cash','in_kind']); // مالي/عيني
    $table->decimal('amount', 12, 2)->nullable(); // للمالي
    $table->string('currency', 8)->default('LYD'); // دينار ليبي افتراضي (يمكن تغييره)
    $table->enum('status', ['pending','paid','verified','cancelled'])->default('pending');

    $table->string('donor_name')->nullable(); // للسماح بتبرع زائر أو باسم مستعار
    $table->string('donor_phone')->nullable();
    $table->string('donor_email')->nullable();
    $table->boolean('is_anonymous')->default(false);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
