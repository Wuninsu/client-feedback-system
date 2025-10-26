<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackEntry extends Model
{
    protected $fillable = [
        'feedback_category',
        'feedback_id',
        'product_id',
        'rating',
        'comment',
        'status'
    ];

    // Relationships

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    // public function feedbackCategory()
    // {
    //     return $this->belongsTo(FeedbackCategory::class, 'feedback_category_id');
    // }
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
