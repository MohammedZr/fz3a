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
        Schema::create('campaigns', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->unsignedBigInteger('owner_id')->nullable(); // user id (مسؤول الحملة)
    $table->decimal('goal_amount', 12, 2)->nullable();
    $table->decimal('raised_amount', 12, 2)->default(0);
    $table->enum('status', ['draft','active','paused','completed'])->default('draft');
    $table->date('starts_at')->nullable();
    $table->date('ends_at')->nullable();
    $table->timestamps();

    $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
