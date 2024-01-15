<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App;

class ProductPrice extends Model
{
    protected $table = 'product_prices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'product_id',
        'type_id',
        'unit_id',
        'city_id',
        'qty_per_unit',
        'unit_price',
        'sale_price',
        'wholesaler',
        'distributor',
        'retailer',
        'buyer',
        'flag',
        'is_active'
    ];

    public $timestamps = false;

    protected $appends = ['unit_name', 'city_name'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'type_id' => 'integer',
        'unit_id' => 'integer',
        'city_id' => 'integer',
        'unit_price' => 'float',
        'sale_price' => 'float',
        'wholesaler' => 'float',
        'retailer' => 'float',
        'buyer' => 'float',
        'flag' => 'boolean',
        'is_active' => 'boolean'
    ];

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
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    /**
     *
     */
    public function unit()
    {
        return $this->belongsTo('App\Unit', 'unit_id');
    }

    /**
     *
     */
    public function getProductPriceByProductId($productId, $typeId = 0, $cityId = 0)
    {
        // For Chabhuoy there is no typeId and cityId
        if (!env('MEMBERSHIP_PRICE')) {
            $typeId = 0;
            $cityId = 0;
        }
        
        $locale = App::getLocale();

        $cityIds = [$cityId];
        if ($cityId > 0) {
            $cityIds = [0, $cityId];
        }

        // Check the membership type
        $price_field = 'buyer';
        if ($typeId == 1) {
            $price_field = 'retailer';
        }
        if ($typeId == 2) {
            $price_field = 'wholesaler';
        }
        if ($typeId == 3) {
            $price_field = 'distributor';
        }
        //DB::enableQueryLog(); // Enable query log
        $query = DB::table('product_prices as pp')
            ->join('unit_translations as un', 'un.unit_id', 'pp.unit_id')
            ->where([
                ['pp.product_id', $productId],
                ['pp.' . $price_field, '>', 0],
                ['un.locale', $locale],
                ['pp.is_active', 1]
            ])
            ->whereIn('pp.city_id', $cityIds)
            ->select(
                'pp.' . $price_field . ' as sale_price',
                'pp.' . $price_field . ' as unit_price',
                'pp.unit_id',
                'pp.qty_per_unit',
                'pp.flag',
                'un.name as unit_name'
            );

        $productPrices = $query->get();
        //dd(DB::getQueryLog()); // Show results of log
        return $productPrices;
    }

    public function getUnitNameAttribute()
    {
        if (array_key_exists('unit_id', $this->attributes)) {
            $unit = UnitTranslation::where(
                [
                    'unit_id' => $this->attributes['unit_id'],
                    'locale'  => \App::getLocale(),
                ]
            )
                ->first();
            return $unit ? $unit->name : '';
        }

        return '';
    }

    public function getCityNameAttribute()
    {
        if (array_key_exists('city_id', $this->attributes)) {

            if ($this->attributes['city_id'] === 0)
                return 'All City';

            $city = CityTranslation::where(
                [
                    'city_province_id' => $this->attributes['city_id'],
                    'locale'  => \App::getLocale(),
                ]
            )
                ->first();
            return $city ? $city->name : '';
        }

        return '';
    }
}
