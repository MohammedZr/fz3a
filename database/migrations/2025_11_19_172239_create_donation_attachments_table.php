<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('donation_attachments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('donation_id')->constrained()->onDelete('cascade');
        $table->string('path');   // مسار الصورة أو الملف
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('donation_attachments');
}

};
