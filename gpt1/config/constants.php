<?php
    return [
        'notification' => [
            'to_user' => [
                'buyer'     => 'buyer',
                'seller'    => 'seller',
                'supplier'  => 'supplier'
            ],
            'notification_type' => [
                'hide' => 'hide',
                'show' =>  'show'
            ],
            'notificatioin_action' => [
                'execute_order' => 'execute_order',
                'accept_order'  => 'accept_order',
                'reject_order'  => 'reject_order',
                'complete_order' => 'complete_order',
                'update_order_status' => 'update_order_status',
                'open_shop'     => 'open_shop',
                'accept_shop'   => 'accept_shop',
                'reject_shop'   => 'reject_shop',
                'promotion'          => 'promotion'
            ]
        ],
        'event_script' => [
            'notification' => [
                'execute_order_notification' => 'execute_order_notification',
                'accept_order_notification'  => 'accept_order_notification',
                'update_order_status_notification'  => 'update_order_status_notification',
                'complete_order_notification' => 'complete_order_notification',
                'open_shop_notification'     => 'open_shop_notification',
                'accept_shop_notification'   => 'accept_shop_notification',
                'reject_shop_notification'   => 'reject_shop_notification'
            ]
        ]

    ];