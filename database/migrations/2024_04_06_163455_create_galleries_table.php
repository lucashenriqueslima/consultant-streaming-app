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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('link')->nullable();
            $table->string('path')->nullable();
            $table->string('image')->nullable();
            $table->string('image_mobile')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
