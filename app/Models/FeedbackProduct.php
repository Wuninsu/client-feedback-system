<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FeedbackProduct extends Pivot
{
    protected $table = 'feedback_products';
    protected $fillable = ['feedback_id', 'product_id'];

    public $timestamps = false;
}
