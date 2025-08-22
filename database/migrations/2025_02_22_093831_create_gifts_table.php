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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->enum('type', ['gift_card', 'coupon_code', 'buy_one_get_one']); // Type of gift
            $table->decimal('gift_card_value', 8, 2)->nullable(); // For gift cards, value between 0 and 10
            $table->string('coupon_code')->nullable(); // For coupon codes
            $table->boolean('buy_one_get_one')->default(false); // If it's a BOGO offer
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
