<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = ['extra_code', 'product_id', 'color_name', 'size_name', 'full_name', 'phone', 'email', 'qty', 'payment_type', 'status', 'order_date', 'shipping_address', 'note', 'created_at', 'updated_at', 'deleted_at'];
}
