<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $fillable = ['key', 'value'];

    public static function setupData()
    {
        return self::pluck('value', 'key')->toArray();
    }
    public $timestamps = false;
}
