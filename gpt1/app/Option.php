<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    public $table = 'options';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];

    /**
     * Get all of the option_value for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function option_value()
    {
        return $this->hasMany(OptionValue::class, 'option_id', 'id');
    }
}
