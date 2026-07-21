<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DietItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'diet_plan_id',
        'meal_name',
        'food_name',
        'quantity',
        'calories',
        'protein',
        'carbohydrates',
        'fat',
        'scheduled_time',
        'date',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dietPlan(): BelongsTo
    {
        return $this->belongsTo(DietPlan::class);
    }
}
