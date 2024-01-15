<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'action_id',
        'type',
        'title',
        'body',
        'is_read',
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
    public function getNotification($id){
        $notification = Notification::where('id', $id)->select('notifications.*')->first();
        return $notification;
    }
    
    /**
     *
     */
    public function getNotificationList($userId, $isRead = 0){
        return DB::table('notifications as noti')
        ->join('event_script_contents as esc', 'esc.id', '=', 'noti.event_script_content_id')
        ->select('noti.id', 'noti.order_id as orderId', 'noti.shop_id as shopId', 
        'noti.to_user as toUser', 'noti.order_flag as orderFlag', 'noti.json_replace as jsonReplace', 
        'noti.notification_action as notificationAction', 'esc.title', 'esc.body', 'noti.created_at')
        ->where('noti.user_id', $userId)
        ->where('noti.is_read', $isRead)
        ->where('esc.language', $language)
        ->orderBy("noti.created_at", "desc")
        ->limit(50)
        ->get();
    }
}
