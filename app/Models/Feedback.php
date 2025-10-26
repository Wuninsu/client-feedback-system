<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'guest_user_id',
        'status',        // e.g., pending, sent, received
        'is_resolved',   // true/false
        'token',         // for public access
    ];


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guestUser()
    {
        return $this->belongsTo(GuestUser::class);
    }


    public function responses()
    {
        return $this->hasMany(FeedbackResponse::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'feedback_products')->using(FeedbackProduct::class);
    }

    public function entries()
    {
        return $this->hasMany(FeedbackEntry::class);
    }

    public function log()
    {
        return $this->hasOne(SentLog::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id'); // assuming users table
    }

    public function feedbackEntries()
    {
        return $this->hasMany(\App\Models\FeedbackEntry::class);
    }

}
