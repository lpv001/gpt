<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use DB;
use App\Product;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'depth',
        'slug'
    ];

    public $timestamps = false;

    protected $hidden = array('lft', 'rgt', 'depth', 'slug', 'order', 'is_active', 'created_at', 'updated_at');

    /**
     *
     */
    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/categories';
    }

    /**
     *
     */
    public function categories()
    {
        return $this->hasMany('APP\Category', 'parent_id', 'id')
        ->with('subCategories')
        ->join('category_translations', 'category_translations.category_id', '=', 'categories.id')
        ->select(
                'categories.id', 
                'parent_id', 
                'slug', 
                'order', 
                'image_name', 
                'name', 
                'name as default_name')
        ->where('category_translations.locale', App::getLocale());
    }

    /**
     *
     */
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')
        ->with('subCategories')
        ->join('category_translations', 'category_translations.category_id', '=', 'categories.id')
        ->select('categories.id', 'parent_id', 'slug', 'order', 'image_name', 'name', 'name as default_name')
        ->where('category_translations.locale', App::getLocale());
    }

    /**
     *
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     *
     */
    public function getCategories($parent_id)
    {
        return Category::where('categories.parent_id', $parent_id)
                    ->where('category_translations.locale', App::getLocale())
                    ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                    ->with('categories')->get([
                        'categories.id', 
                        'parent_id', 
                        'slug', 
                        'order',
                        'image_name',
                        'name', 
                        'name as default_name']);
    }

    /**
     *
     */
    public function getIdentity()
    {
        return $this->id;
    }

    /**
     * Get all of the comments for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sub_category()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     *
     */
    public function getListofCategory()
    {
        $locale = App::getLocale();

        return Category::where([
            ['categories.is_active', 1],
            // ['categories.parent_id', 0],
            ['c.locale', $locale]
        ])
            ->join('category_translations as c', 'c.category_id', '=', 'categories.id')
            ->select(
                'categories.*',
                'c.name as default_name',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", categories.image_name) AS image_name')
            )
            ->get();
    }

    /**
     *
     */
    public function getListofSubCategory($parent_id)
    {
        $locale = App::getLocale();

        return Category::where([
            ['categories.is_active', 1],
            ['categories.parent_id', $parent_id],
            ['c.locale', $locale]
        ])
            ->join('category_translations as c', 'c.category_id', '=', 'categories.id')
            ->select(
                'categories.*',
                'c.name as default_name',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", categories.image_name) AS image_name')
            )
            ->get();
    }

    /**
     *
     */
    public function getDetailofCategory($category_id)
    {
        return Category::findOrFail($category_id)->select(
            'categories.*',
            DB::raw('CONCAT("' . $this->getImageURL() . '/", categories.image_name) AS image_name')
        )
            ->first();
    }

    /**
     *
     */
    public function getProductbyCategory($category_id)
    {
        return Product::with('categories')->where('category_id', $category_id)
            ->select('categories.*', DB::raw('CONCAT("' . $this->getImageURL() . '/", categories.image_name) AS image_name'))
            ->get();
    }

    /**
     * Get Name of category
     */
    public function getNameofCategory($name)
    {
        return Category::select('categories.*')->where('categories.is_active', 1)
            ->where('name', 'like', '%' . $name . '%')
            ->select('categories.*', DB::raw('CONCAT("' . $this->getImageURL() . '/", categories.image_name) AS image_name'))
            ->get();
    }

    // BTY: asset is giving wrong path, so I disable this
    public function getImageNameAttributeaaaa()
    {
        return asset('/uploads/images/categories/icon/icon_' . $this->attributes['image_name']);
    }
}
