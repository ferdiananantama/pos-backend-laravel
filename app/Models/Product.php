<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'price',
        'stock',
        'status',
        'is_favorite',
        'category_id' //id category
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
