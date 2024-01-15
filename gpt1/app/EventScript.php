<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventScript extends Model
{
    protected $table = 'event_scripts';
    protected $primaryKey = 'id';
	protected $fillable = [
        'key_name',
        'type'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function getEventScriptIdByKeyName($keyName) {
        return EventScript::where('key_name', $keyName)->first()->id;
    }
}
