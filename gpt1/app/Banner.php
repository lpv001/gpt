<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'description',
        'image',
        'target_url',
        'expiry_date',
        'is_active'
    ];

    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/banners';
    }

    public function getIdentity(){
        return $this->id;
    }

    /**
     * Get list of active banners
     * @Author: BTY
     */
    public function getActiveBannerList(){
        return Banner::where('is_active', 1)
        ->select('banners.*',DB::raw('CONCAT("' . $this->getImageURL() . '/", banners.image) AS image'))
        ->get();
    }
}
