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
        Schema::create('leaf_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');

            $table->string('image')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');

            $table->index('sub_category_id');

            $table->json('spec_fields')->nullable();
            $table->string('v1st')->nullable();
            $table->string('v2nd')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaf_categories');
    }
};
