<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['categories_id', 'name', 'slug', 'description', 'summary', 'image', 'list_image', 'price', 'sale_price', 'star', 'like_pd', 'is_sale', 'quantity', 'status', 'created_at', 'updated_at']; 
}
