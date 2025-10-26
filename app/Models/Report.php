<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'filters',
        'generated_by',
    ];

    // Relationships
    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
