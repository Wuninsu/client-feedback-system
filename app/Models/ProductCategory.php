<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = "product_categories";
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
