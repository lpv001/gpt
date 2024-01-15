<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\OrderItem;
use App\Order;
use App\Shop;
use App\SystemLog;
use App\Http\Controllers\API\FirebaseController;
use Validator;
use Carbon\Carbon;
use App\User;

class SystemLogsController extends Controller
{
    /**
     * Test order process
     * @author: Sambath, Channa, BTY
     */
    public function testOrder(Request $request) {
        $shop = new Shop;
        $supplier = null;
        $distance = 1;

        $shopsNearby1 = $shop->getShopsNearby(11.594390, 104.902975, 2, Auth::user()->id);
        
        // New rule from Ganzberg that if no shops available, pass this to factory
        $shopsNearby2 = DB::table("shops as sh")
            ->select(DB::raw("sh.id, sh.name, sh.address, sh.lat, sh.lng, us.fcm_token, us.device_type"))
            ->join('users as us', 'us.id', '=', 'sh.user_id')
            ->where('sh.user_id', '!=', Auth::user()->id) // not the current login user
            ->where('sh.is_active', '=', 1) // accepting only approved shops
            ->where('us.membership_id', '=', 4) // only factory
            ->get();

        $shopsNearby = $shopsNearby1->merge($shopsNearby2);
        
        dd($shopsNearby);
        return;

        
    }
}
