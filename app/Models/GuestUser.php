<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'ip_address',
        'device',
    ];

    // Relationships
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function sentLogs()
    {
        return $this->hasMany(SentLog::class);
    }
}
