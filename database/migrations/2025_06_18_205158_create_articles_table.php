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

        Schema::create('articles', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedBigInteger('author_id');
            $table->string('title', 191);
            $table->string('seo_title', 191)->nullable();
            $table->text('excerpt')->nullable();
            $table->text('body');
            $table->string('slug', 191)->unique();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->enum('status', ['PUBLISHED', 'DRAFT', 'PENDING'])->default('DRAFT');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('author_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
