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
            $table->uuid()->unique()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('image')->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('product_category_id');  // ensure matching data type
            $table->timestamps();

            // Add the foreign key constraint
            $table->foreign('product_category_id')
                ->references('id')->on('product_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
