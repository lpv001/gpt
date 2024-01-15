<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\Point;
use App\Admin\Models\Setting;
use App\Admin\Models\Shop;
use App\Admin\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class PointController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = User::pluck('full_name', 'id')->toArray();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return Point::all();
        if ($request->ajax()) {
            return DataTables::of(Point::query())
                ->editColumn('user_id', function ($data) {
                    $user = User::where('id', $data->user_id)->first();
                    return $user ? $user->full_name : '';
                })
                ->editColumn('shop_id', function ($data) {
                    $shop = Shop::find($data->shop_id);
                    return $shop ? $shop->name : '';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route('point.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <a type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs ml-2"><i class="glyphicon glyphicon-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin::promotions.point.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = $this->user;
        return view('admin::promotions.point.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validateRequest($request);
            $input = $request->all();
            $input['shop_id'] = 0;
            $input['order_id'] = 1;
            $input['flag'] = 1;
            $input['status'] = 1;

            $point = Point::create($input);

            Flash::success('Point saved successfully.');
            return redirect(route('point.index'));
        } catch (Exception $e) {
            return $e->getMessage();
            Flash::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->user;
        $point = Point::find($id);
        return view('admin::promotions.point.edit', compact('point', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validateRequest($request);
            $input = $request->all();
            // $input['shop_id'] = 0;
            // $input['order_id'] = 1;
            // $input['flag'] = 1;
            // $input['status'] = 1;

            $point = Point::find($id)->update($input);
            Point::find($id)->update();

            Flash::success('Point saved successfully.');
            return redirect(route('point.index'));
        } catch (Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $point = Point::find($id);
        if ($point) {
            $point->delete();

            return response()->json([
                'status'   =>   true,
                'message'   =>  'Deleted Successfully!'
            ], 200);
        }

        return response()->json([
            'status'   =>   false,
            'message'   =>  'Not Found!'
        ], 200);
    }

    public function validateRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'total' => 'required',
        ]);
    }
}
