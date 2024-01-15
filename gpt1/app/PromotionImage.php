<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionImage extends Model
{
    protected $table = 'promotion_images';
    protected $primaryKey = 'id';
	protected $fillable = [
        'promotion_id',
        'image_name',
        'order'
    ];
    public $timestamps = false;
    public function getIdentity(){
        return $this->id;
    }

    public function promotion() {
        return $this->belongsTo('App\Promotion','promotion_id');
    }
}
