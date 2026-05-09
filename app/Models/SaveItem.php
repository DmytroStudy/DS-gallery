<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveItem extends Model
{
    protected $table = 'DS_SaveItems';
    protected $primaryKey = 'save_id';

    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
