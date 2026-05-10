<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'DS_ProductImages';
    protected $primaryKey = 'product_image_id';

    protected $fillable = ['product_id', 'img_path', 'order'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
