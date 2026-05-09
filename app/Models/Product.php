<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'DS_Products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'title',
        'artist_id',
        'year',
        'genre',
        'category',
        'price',
        'description',
        'image'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'artist_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'product_id');
    }

    public function savedItems()
    {
        return $this->hasMany(SaveItem::class, 'product_id', 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }
}
