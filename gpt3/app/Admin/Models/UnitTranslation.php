<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class UnitTranslation extends Model
{
    //
    protected $table = 'unit_translations';
    protected $fillable = ['unit_id', 'name', 'locale'];

    public $timestamps = false;
}
