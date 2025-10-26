<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'sku',
        'status',
        'image',
        'price',
    ];

    public function feedbackEntries()
    {
        return $this->hasMany(FeedbackEntry::class);
    }

    public function feedback()
    {
        return $this->belongsToMany(Feedback::class, 'feedback_products')->using(FeedbackProduct::class);
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }


    public function getRouteKeyName()
    {
        return 'uuid';
    }
    protected $guarded = ['uuid'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
