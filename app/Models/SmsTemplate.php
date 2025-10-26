<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'body',
        'is_active',
    ];

    // Relationships
    public function sentLogs()
    {
        return $this->hasMany(SentLog::class, 'template_id');
    }
}
