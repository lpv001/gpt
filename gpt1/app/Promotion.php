<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App;
use App\PromotionTypeTranslation;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'promotion_type_id',
        'value',
        'qty',
        'balance',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function getNameAttribute()
    {
        if (!array_key_exists('promotion_type_id', $this->attributes))
            return '';

        $promotion_type = PromotionTypeTranslation::where([
            'promotion_type_id' => $this->attributes['promotion_type_id'],
            'locale' => \App::getLocale()
        ])->first();
        return $promotion_type ? $promotion_type->name : '';
    }

    /**
     *
     */
    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/promotions';
    }

    /**
     *
     */
    public function promotion_types()
    {
        return $this->belongsTo('App\PromotionType', 'promotion_type_id');
    }

    /**
     *
     */
    public function images()
    {
        return $this->hasMany('App\PromotionImage')->orderBy('order', 'ASC');
    } // EOF

    /**
     *
     */
    public function getPromotionList($params = null, $limit = 30, $page = 1)
    {
        $locale = App::getLocale();

        $query = DB::table('promotions as p')
            ->join('promotion_translations as pt', 'pt.promotion_id', '=', 'p.id')
            ->join('promotion_type_translations as c', 'c.promotion_type_id', '=', 'p.promotion_type_id')
            ->leftJoin('promotion_images as pi', 'p.id', '=', 'pi.promotion_id')
            ->select(
                'p.id',
                'pt.name',
                'pt.description',
                'p.unit_price',
                'p.qty',
                'p.start_date',
                'p.end_date',
                'p.promotion_type_id',
                'c.name as promotion_type_name',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pi.image_name) AS image_name')
            )
            ->where([
                ['pt.locale', $locale],
                ['c.locale', $locale],
                ['p.is_active', 1]
            ])
            ->groupBy('p.id')
            ->skip(($page - 1) * $limit)->take($limit);

        $promotions = $query->get();
        return $promotions;
    } // EOF

    /**
     *
     */
    public function getPromotionDetail($promotion_id, $params = null)
    {
        $query = DB::table('promotions as p')
            ->leftJoin('promotion_images as pi', 'p.id', '=', 'pi.promotion_id')
            ->select('p.*', DB::raw('CONCAT("' . $this->getImageURL() . '/", pi.image_name) AS image_name'))
            ->where('p.id', $promotion_id);
        return $query->first();
    } // EOF

}
