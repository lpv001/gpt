<?php

namespace App\Frontend\Models;

use App\Admin\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    protected $table = 'categories';
    public $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'depth',
        'default_name',
        'slug',
        'order',
        'image_name',
        'is_active'
    ];

    static $url = env('FRONTEND_URL') . '/uploads/images/categories';

    /**
     * 
     */
    static function getListofCategory()
    {
        return DB::table('categories')->where('is_active', 1)->select(
            'categories.*',
            DB::raw('CONCAT("' . self::$url . '/", categories.image_name) AS image_name')
        )
            ->get();
    }

    /**
     * Get the translation associated with the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function translate()
    {
        return $this->hasOne(CategoryTranslation::class, 'category_id', 'id');
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

    public static function getCategory()
    {
        $data = [];
        $categories = Category::where('parent_id', 0)->with([
            'translate' => function ($query) {
                return $query->where('locale', \App::getLocale())->select('name', 'category_id');
            },
            'sub_category.translate' => function ($query) {
                return $query->where('locale', \App::getLocale())->select('name', 'category_id');
            }
        ])->select('id', 'parent_id')->get();

        return $categories;
    }
}
