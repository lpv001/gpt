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
class PromotionTypeTranslation extends Model
{
    public $table = 'promotion_type_translations';

    public $timestamps = false;

    public $fillable = [
        'promotion_type_id',
        'name',
        'locale',
    ];
}
