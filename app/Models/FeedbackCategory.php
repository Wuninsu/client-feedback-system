<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackCategory extends Model
{
    protected $table="feedback_categories";
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // Relationships
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
