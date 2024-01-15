<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
//use Eloquent as Model;

/**
 * Class Admin
 * @package App\Admin\Models
 * @version July 31, 2019, 7:22 am UTC
 *
 * @property string email
 * @property string password
 * @property string remember_token
 */
class Promotion extends Model
{
    public $table = 'promotions';



    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'code',
        'promotion_type_id',
        'value',
        'qty',
        'start_date',
        'end_date',
        'is_active',
        'flag',
        'image',
    ];

    protected $appends = ['promotion_type', 'name_en', 'name_km', 'image_url'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    public function getPromotionTypeAttribute()
    {
        if (array_key_exists('promotion_type_id', $this->attributes)) {
            $translate = PromotionTypeTranslation::where(['promotion_type_id' => $this->attributes['promotion_type_id'], 'locale' => 'en'])->first();
            return $translate ? $translate->name : '';
        }
    }

    /**
     * Get the promotion name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameEnAttribute()
    {
        $translate = PromotionTranslation::where(['promotion_id' => $this->attributes['id'], 'locale' => 'en'])->first();
        return $translate ? $translate->name : '';
    }

    public function getNameKmAttribute()
    {
        $translate = PromotionTranslation::where(['promotion_id' => $this->attributes['id'], 'locale' => 'km'])->first();
        return $translate ? $translate->name : '';
    }

    // public function getImagePathAttribute()
    // {
    //     return  public_path('/uploads/images/redeem/') . $this->attributes['image'];
    //     // return  asset('/uploads/images/redeem' . '/' . $this->attributes['image']);
    // }

    public function getImageUrlAttribute()
    {
        if (array_key_exists('image', $this->attributes)) {
            return  asset('/uploads/images/redeem' . '/' . $this->attributes['image']);
        }
    }
}
