<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $table = 'order_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'logs',
        'flag'
    ];
    protected $casts = ['logs' => 'array'];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = false;

    /**
     *
     */
    public function getIdentity()
    {
        return $this->id;
    }

    /**
     * Get logs
     */
    public function getLog($order_id)
    {
        return DB::table('order_logs as ol')
            ->select('ol.order_id', 'ol.logs', 'ol.flag')
            ->where('ol.order_id', $order_id)
            ->get();
    } // EOF
}
