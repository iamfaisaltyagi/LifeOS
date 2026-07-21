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
        Schema::table('tasks', function (Blueprint $table) {
            $table->time('due_time')->nullable()->after('due_date');
            $table->enum('repeat_type', ['none', 'daily', 'weekly', 'monthly', 'custom'])->default('none')->after('due_time');
            $table->unsignedSmallInteger('repeat_every')->nullable()->after('repeat_type');
            $table->date('repeat_until')->nullable()->after('repeat_every');
            $table->uuid('recurrence_group')->nullable()->after('repeat_until');

            $table->index(['user_id', 'repeat_type']);
            $table->index(['user_id', 'recurrence_group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'repeat_type']);
            $table->dropIndex(['user_id', 'recurrence_group']);
            $table->dropColumn([
                'due_time',
                'repeat_type',
                'repeat_every',
                'repeat_until',
                'recurrence_group',
            ]);
        });
    }
};
