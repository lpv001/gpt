<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use DB;
use App\Product;

class CategoryProduct extends Model
{
    protected $table = 'category_product';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'category_id',
        'product_id',
    ];

    public $timestamps = false;

    /**
     * Get all of the category for the Category copy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category()
    {
        return $this->hasMany(Category::class, 'id', 'category_id');
    }
}
