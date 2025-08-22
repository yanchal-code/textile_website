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

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('alt_image_text')->nullable();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('leaf_category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();

            $table->string('design_number')->nullable();

            $table->boolean('has_variations')->default(false);
            // color field is variable 1 size field is variable 2
            $table->string('color')->nullable();
            $table->string('size')->nullable();

            $table->string('sku')->unique();
            $table->double('price', 10, 2)->nullable();
            $table->double('compare_price', 10, 2)->nullable();

            $table->unsignedInteger('quantity')->nullable();

            $table->text('short_description')->nullable();
            $table->text('description')->nullable();

            $table->double('shipping', 10, 2)->default(0);
            $table->double('shippingAddons', 10, 2)->default(0);

            $table->string('h_time')->nullable();
            $table->string('d_time')->nullable();
            $table->string('s_services')->nullable();

            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_bidding')->default(false);

            $table->json('specs')->nullable();

            $table->timestamps();
        });

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('color')->nullable();;
            $table->string('size')->nullable();;
            $table->string('sku')->unique();
            $table->double('price', 10, 2);
            $table->double('c_price', 10, 2)->nullable();

            $table->unsignedInteger('quantity');
            $table->tinyInteger('status')->default(1);

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->string('image');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variation_id')->references('id')->on('product_variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variations');
        Schema::dropIfExists('products');
    }
};
