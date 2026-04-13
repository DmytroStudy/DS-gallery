<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'artwork_id',
        'quantity',
        'title',
        'artist',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class, 'artwork_id', 'artwork_id');
    }
}
