<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'buyer_name',
        'phone',
        'address',
        'payment_method',
        'total_price',
        'discount',
        'final_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
