<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'number',
        'total_price',
        'shipping_price',
        'status',
        'payment_method',
        'notes',
        'address_info'
    ];

    protected $casts = [
        'address_info' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
