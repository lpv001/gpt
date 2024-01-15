<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Point extends Model
{
    protected $table = 'points';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'shop_id',
        'order_id',
        'title',
        'total',
        'flag',
        'status'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = true;

    /**
     *
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    /**
     *
     */
    public function shop(){
        return $this->hasMany('App\Shop','shop_id');
    }

    /**
     * Get order list
     */
    public function getTotalPoints($user_id, $status = null){
        $query = null;

        $query = DB::table('points as p')
            ->join('users as u', 'u.id', 'p.user_id')
            ->select('u.full_name as user_name', 'p.user_id', DB::raw('SUM(p.total) as total_points'))
            ->groupBy('p.user_id')
            ->where('p.user_id', $user_id);
            
            if (is_numeric($status)) {
                $query->where('p.status', $status);
            }
        return $query->first();
    } // EOF

    /**
     * Get order list
     */
    public function getPoints($user_id, $page=1, $limit=30){
        $query = null;

        $query = DB::table('points as p')
            ->join('users as u', 'u.id', 'p.user_id')
            ->skip(($page - 1) * $limit)->take($limit)
            ->select('u.full_name as user_name', 'p.*')
            ->orderBy('p.created_at', 'DESC');
        $query->where('p.user_id', $user_id);
        return $query->get();
    } // EOF
}
