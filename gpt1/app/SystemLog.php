<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_logs';
    protected $primaryKey = 'id';
	protected $fillable = [
        'module'
    ];
    protected $casts = ['logs' => 'array'];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = false;
    
    public function getIdentity()
    {
        return $this->id;
    }
}
