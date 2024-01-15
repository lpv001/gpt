<?php

namespace App;

use App\ShopCategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\User;

class Shop extends Model
{
    protected $table = 'shops';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'about',
        'logo_image',
        'cover_image',
        'phone',
        'user_id',
        'country_id',
        'city_province_id',
        'district_id',
        'address',
        'lng',
        'lat',
        'membership_level',
        'membership_id',
        'supplier_id',
        'status',
        'shop_category_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    protected $appends = ['membership_name', 'shop_category_name', 'city', 'district', 'supplier_name'];

    public $timestamps = true;

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
    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/shops';
    }

    /**
     *
     */
    public function getIdentity()
    {
        return $this->id;
    }

    /**
     *
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'shop_id');
    }

    /**
     *
     */
    public function getShopsNearby($lat, $lng, $distance, $userId)
    {
        // At least default distance is 1
        if ($distance < 0) {
            $distance = 1;
        }

        return DB::table("shops as sh")
            ->join('users as us', 'us.id', '=', 'sh.user_id')
            ->select(
                DB::raw("sh.id, sh.name, sh.address, sh.lat, sh.lng, us.fcm_token, us.device_type, sh.user_id"),
                DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(sh.lat)) 
                * cos(radians(sh.lng) - radians(" . $lng . ")) 
                + sin(radians(" . $lat . ")) 
                * sin(radians(sh.lat))) AS distance")
            )
            ->where('sh.user_id', '!=', $userId)
            ->where('sh.is_active', '=', 1)
            ->where('sh.membership_id', '>', 0)
            ->having('distance', '<', $distance)
            ->orderBy('distance')
            ->get();
    }

    /**
     *
     */
    public function District()
    {
        return $this->belongTo('App\Districts', 'district_id');
    }

    /**
     *
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /**
     *
     */
    public function getShop($id)
    {
        $shop = Shop::where('id', $id)->select(
            'shops.*',
            DB::raw('CONCAT("' . $this->getImageURL() . '/", shops.logo_image) AS logo_image'),
            DB::raw('CONCAT("' . $this->getImageURL() . '/", shops.cover_image) AS cover_image')
        )->first();
        return $shop;
    }

    /**
     * Find shop by suppiler_id and status_id
     */
    public function getMemberships($supplier_id, $status_id)
    {
        if ($status_id != null) {
            return Shop::where('supplier_id', $supplier_id)
                ->where('is_active', $status_id)
                ->get();
        } else {
            return Shop::where('supplier_id', $supplier_id)->get();
        }
    }

    /**
     * get list of applied shops
     * @status = {'all', 0 = pending, 1=accepted, 10=rejected}
     * @Author: Sambath, BTY
     */
    public function getShopApplied($shopId, $status = null, $page = 1, $limit = 10)
    {
        $query = DB::table('shops as s')
            ->join('users as u', 'u.id', 's.user_id')
            ->join('country as c', 'c.id', 's.country_id')
            ->join('city_provinces as cp', 'cp.id', 's.city_province_id')
            ->join('districts as d', 'd.id', 's.district_id')
            ->where('s.supplier_id', $shopId)
            ->select(
                's.*',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", s.logo_image) AS logo_image'),
                DB::raw('CONCAT("' . $this->getImageURL() . '/", s.cover_image) AS cover_image'),
                'c.default_name as country_name',
                'cp.default_name as city_name',
                'd.default_name as district_name',
                's.supplier_id',
                'u.id as user_id',
                'u.full_name as user_name'
            )
            ->orderBy('s.id', 'desc')
            ->skip(($page - 1) * $limit)->take($limit);

        if (is_numeric($status)) {
            $query->where('s.status', $status);
        }

        $shops = $query->get();
        return $shops;
    }

    /**
     *
     */
    public function getStat($supplier_id, $status = null)
    {
        $query = DB::table('shops as s')
            ->where('s.supplier_id', $supplier_id);

        if (is_numeric($status)) {
            $query->where('s.status', $status);
        }
        return $query->count();
    }

    public function getMembershipNameAttribute()
    {
        if (array_key_exists('membership_id', $this->attributes)) {
            $membership =  \DB::table('membership_translations')
                ->where('membership_id', $this->attributes['membership_id'])
                ->where('locale', \App::getLocale())
                ->first(['id', 'name']);

            return $membership ? $membership->name : '';
        }
        return '';
    }

    public function getShopCategoryNameAttribute()
    {
        if (array_key_exists('shop_category_id', $this->attributes)) {
            $shop_categories = ShopCategoryTranslation::where([
                'shop_category_id'  =>  $this->attributes['shop_category_id'],
                'locale' => \App::getLocale()
            ])
                ->first();
            return $shop_categories ? $shop_categories->name : '';
        }

        return '';
    }

    public function getCityAttribute()
    {
        if (array_key_exists('city_province_id', $this->attributes)) {
            $city = CityProvinces::where('id', $this->attributes['city_province_id'])->first();

            return $city ? $city->default_name : '';
        }

        return '';
    }

    public function getDistrictAttribute()
    {
        if (array_key_exists('district_id', $this->attributes)) {
            $city = Districts::where('id', $this->attributes['district_id'])->first();

            return $city ? $city->default_name : '';
        }

        return '';
    }

    public function getLogoImageAttribute()
    {
        return env('PUB_URL') . '/uploads/images/shops/' . $this->attributes['logo_image'];
    }

    public function getCoverImageAttribute()
    {
        return env('PUB_URL') . '/uploads/images/shops/' . $this->attributes['cover_image'];
    }

    public function getSupplierNameAttribute()
    {
        $supplier = Shop::where('id', $this->attributes['supplier_id'])->first();
        return $supplier ? $supplier->name : '';
    }
}
