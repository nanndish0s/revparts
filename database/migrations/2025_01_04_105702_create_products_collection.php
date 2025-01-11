<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateProductsCollection extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('products', function (Blueprint $collection) {
            // Indexes
            $collection->index('name');
            $collection->index('category');

            // Fields matching the dashboard form
            $collection->string('name')->required();
            $collection->text('description')->nullable();
            $collection->decimal('price', 10, 2)->required();
            $collection->integer('stock_quantity')->required();
            $collection->string('category')->required();
            $collection->string('product_image')->nullable(); // For image file path

            // Timestamps
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('products');
    }
}
