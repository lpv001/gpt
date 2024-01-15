<?php

namespace App\Admin\Models;

use DB;
use Eloquent as Model;
use App\Admin\Models\User;

/**
 * Class Shop
 * @package App\Admin\Models
 * @version September 26, 2019, 1:45 pm UTC
 *
 * @property integer user_id
 * @property integer supplier_id
 * @property string name
 * @property string about
 * @property string logo_image
 * @property string cover_image
 * @property string phone
 * @property integer country_id
 * @property integer city_province_id
 * @property integer district_id
 * @property string address
 * @property float lat
 * @property float lng
 * @property integer membership_id
 * @property boolean is_active
 * @property integer status
 */
class Shop extends Model
{

    public $table = 'shops';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'shop_category_id',
        'user_id',
        'supplier_id',
        'name',
        'about',
        'logo_image',
        'cover_image',
        'phone',
        'country_id',
        'city_province_id',
        'district_id',
        'address',
        'lat',
        'lng',
        'membership_id',
        'is_active',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'supplier_id' => 'integer',
        'name' => 'string',
        'about' => 'string',
        'logo_image' => 'string',
        'cover_image' => 'string',
        'phone' => 'string',
        'country_id' => 'integer',
        'city_province_id' => 'integer',
        'district_id' => 'integer',
        'address' => 'string',
        'lat' => 'float',
        'lng' => 'float',
        'membership_id' => 'integer',
        'is_active' => 'boolean',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_ids' => 'required',
        'supplier_id' => 'required',
        'name' => 'required',
        'phone' => 'required',
        'country_id' => 'required',
        'city_province_id' => 'required',
        'district_id' => 'required',
        'membership_id' => 'required',
        'is_active' => 'required',
        'status' => 'required',
        'shop_category_id'  =>  'required'

    ];

    /**
     *
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     *
     */
    public function user()
    {
        return $this->belongsTo('App\Admin\Models\User', 'user_id');
    }

    /*
    public function supplier(){
        return $this->belongsTo('App\Admin\Models\Supplier', 'supplier_id');
    }
    
    public function supplier($supplier_id){
        return Shop::where('id', $supplier_id)->select('shops.*')->first();
    }
    */

    public function supplier()
    {
        return $this->belongsTo('App\Admin\Models\Shop', 'supplier_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Admin\Models\City', 'city_province_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Admin\Models\District', 'district_id');
    }

    public function membership()
    {
        return $this->belongsTo('App\Admin\Models\Membership', 'membership_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Admin\Models\CountryModel', 'country_id');
    }
    
    public function countTotal()
    {
        $shops = Shop::all();
        return count($shops);
    }

    public function countCreatedToday()
    {
        $shops = Shop::whereRaw('Date(created_at) = CURDATE()')->get();
        return count($shops);
    }
}
