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
        Schema::create('lessons', function (Blueprint $table): void {
            $table->uuid('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->boolean('can_preview')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->enum('status', ['PROCESSING', 'READY', 'FAILED'])->default('PROCESSING');
            $table->enum('video_driver', ['VIMEO', 'FILE'])->default('FILE');
            $table->string('disk')->nullable();
            $table->string('video_source');
            $table->boolean('is_published')->default(false);
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();

            $table->unique(['module_id', 'position']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
