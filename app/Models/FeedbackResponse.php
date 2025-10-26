<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
    protected $fillable = [
        'feedback_id',
        'responder_id',
        'message',
        'channel'
    ];

    // Relationships
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }
}
