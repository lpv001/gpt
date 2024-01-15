<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total_users = User::count();
        $total_users_today = User::whereDate('created_at', Carbon::today())->count();

        return view('admin::home', compact(
            'total_users',
            'total_users_today'
        ));
    }
}
