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
        Schema::create('plans', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('short_description');
            $table->json('gateway_meta'); // This stores the plan ID fot Stripe, paddle and many others that would be integrated
            $table->decimal('price', 10, 2);
            $table->enum('billing_period', ['monthly', 'yearly', 'lifetime'])->default('lifetime');
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->unique()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
