<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventScriptContent extends Model
{
    protected $table = 'event_script_contents';
    protected $primaryKey = 'id';
	protected $fillable = [
        'event_script_id',
        'title',
        'body',
        'language'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function getEventScriptContentByEventScriptIdAndLanguage($eventScriptId, $language) {
        return EventScriptContent::where('event_script_id', $eventScriptId)
            ->where('language', $language)    
            ->first();
    }
}
