<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class NotificationInfo extends Model
{
    protected $table = 'notification_infos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'to_user',
        'notification_action',
        'is_read',
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function saveNotificationInfo($userId, $title, $body, $toUser,
                    $notificationAction) {
       
        // Save internal notification_info
        $notificationInfo = new NotificationInfo;
        $notificationInfo->user_id = $userId;
        $notificationInfo->title = $title;
        $notificationInfo->body = $body;
        $notificationInfo->to_user = $toUser;
        $notificationInfo->notification_action = $notificationAction;
        $notificationInfo->is_read = 0; //Not yet read
        $notificationInfo->save();
    }

    public function getNofitificationInfo($userId, $isRead = 0) {
        return DB::table('notification_infos as noti')
        ->select('noti.id',
        DB::raw("0 as orderId"), DB::raw("0 as orderFlag"), 
        DB::raw("null as jsonReplace"), DB::raw("0 as shopId"),
        'noti.to_user as toUser', 'noti.notification_action as notificationAction',
         'noti.title', 'noti.body', 'noti.created_at')
        ->where('noti.user_id', $userId)
        ->where('noti.is_read', $isRead)
        ->orderBy("noti.created_at", "desc")
        ->limit(50)
        ->get();
    }
}
