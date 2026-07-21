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
        if (Schema::hasTable('diet_items')) {
            return;
        }

        Schema::create('diet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('diet_plan_id')->nullable();
            $table->string('meal_name');
            $table->string('food_name');
            $table->string('quantity')->nullable();
            $table->unsignedInteger('calories')->default(0);
            $table->decimal('protein', 8, 2)->default(0);
            $table->decimal('carbohydrates', 8, 2)->default(0);
            $table->decimal('fat', 8, 2)->default(0);
            $table->time('scheduled_time')->nullable();
            $table->date('date');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'meal_name']);
            $table->index('diet_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_items');
    }
};
