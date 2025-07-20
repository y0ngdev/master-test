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
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('checkout_session_id')->unique();
            $table->string('payment_intent_id')->nullable();
            $table->decimal('amount', 15, 2);                    // in major units (e.g. dollars)
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['successful'])->default('successful');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
