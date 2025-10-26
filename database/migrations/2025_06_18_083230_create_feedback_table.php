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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('guest_user_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('feedback_category_id')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_resolved')->nullable();
            $table->timestamps();
            $table->foreign('feedback_category_id')
                ->references('id')->on('feedback_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
