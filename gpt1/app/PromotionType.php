<?php

namespace App;

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
class PromotionType extends Model
{
    public $table = 'promotion_types';



    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'order_id',
        'is_active',
    ];

    protected $appends = ['name_en', 'name_km'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    public function getNameEnAttribute()
    {
        $translate = PromotionTypeTranslation::where(['promotion_type_id' => $this->attributes['id'], 'locale' => 'en'])->first();
        return $translate ? $translate->name : '';
    }

    public function getNameKmAttribute()
    {
        $translate = PromotionTypeTranslation::where(['promotion_type_id' => $this->attributes['id'], 'locale' => 'km'])->first();
        return $translate ? $translate->name : '';
    }
}
