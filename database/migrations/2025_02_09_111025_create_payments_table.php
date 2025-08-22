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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->string('gateway');

            // PayPal-specific fields
            $table->string('gateway_email')->nullable();
            $table->string('gateway_payer_id')->nullable();

            // PhonePe-specific fields
            $table->string('phonepe_transaction_id')->nullable();

            // Common payment details
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->string('status'); // "pending", "completed", "failed"

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
