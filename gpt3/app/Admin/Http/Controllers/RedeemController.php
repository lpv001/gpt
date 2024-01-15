<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\Promotion;
use App\Admin\Models\PromotionTranslation;
use App\Admin\Models\PromotionTypeTranslation;
use App\Admin\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class RedeemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            return DataTables::of(Promotion::where('code', 'LIKE', '%redeem%')->get())
                ->editColumn('is_active', function ($data) {
                    return $data->is_active == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">X</span>';
                })
                ->editColumn('image', function ($data) {
                    if (!$data->image) {
                        return '';
                    }

                    return '<img src="' . $data->image_url . '" alt="redeem" widht="70" height="70">';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route('redeem.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <a type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs ml-2"><i class="glyphicon glyphicon-trash"></i></a>';
                })
                ->rawColumns(['action', 'is_active', 'image'])
                ->make(true);
        }

        return view('admin::redeems.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promotion_type = PromotionTypeTranslation::where('locale', \App::getLocale())
            ->whereNotIn('promotion_type_id', [1])->pluck('name', 'promotion_type_id');
        // return $promotion_type;
        return view('admin::redeems.create', compact('promotion_type'));
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

            $image_name = $this->image($request->images);

            $promotion = Promotion::create([
                'code'  =>  'redeem-' . $this->generateCode(),
                'promotion_type_id' =>  $request->promotion_type_id ?? 0,
                'value' =>  $request->value,
                'qty'   =>  $request->qty,
                // 'start_date'    =>  $request->start_date,
                // 'end_date' =>  $request->end_date,
                'is_active' =>  1,
                'flag'  =>  2,
                'image' => $image_name
            ]);

            if (isset($request->name)) {
                foreach ($request->name as $key => $value) {
                    PromotionTranslation::updateOrCreate(
                        [
                            'promotion_id'  =>  $promotion->id,
                            'locale'    =>  $request->locale[$key]
                        ],
                        [
                            'name'  =>  $value ?? $request->name[$key],
                            'description'  =>  $value ?? $request->name[$key],
                        ]
                    );
                }
            }

            Flash::success('Redeem Create successfully.');
            return redirect(route('redeem.index'));
        } catch (Exception $e) {
            // return redirect()->back();
            return $e->getMessage();
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
        //
        $promotion = Promotion::find($id);
        $promotion_type = PromotionTypeTranslation::where('locale', \App::getLocale())
            ->whereNotIn('promotion_type_id', [1])->pluck('name', 'promotion_type_id');

        // return $promotion;
        return view('admin::redeems.edit', compact('promotion', 'promotion_type'));
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
            $promotion = Promotion::find($id);
            $image_name = $this->image($request->images, $promotion->image);

            $promotion->update([
                'promotion_type_id' =>  $request->promotion_type_id ?? 0,
                'value' =>  $request->value,
                'qty'   =>  $request->qty,
                // 'start_date'    =>  $request->start_date,
                // 'end_date' =>  $request->end_date,
                'is_active' =>  1,
                'flag'  =>  2,
                'image' =>  $image_name != '' ? $image_name : $promotion->image,
            ]);

            if (isset($request->name)) {
                foreach ($request->name as $key => $value) {
                    PromotionTranslation::updateOrCreate(
                        [
                            'promotion_id'  =>  $id,
                            'locale'    =>  $request->locale[$key]
                        ],
                        [
                            'name'  =>  $value ?? $request->name[$key],
                            'description'  =>  $value ?? $request->name[$key],
                        ]
                    );
                }
            }

            Flash::success('Redeem updated successfully.');
            return redirect(route('redeem.index'));
        } catch (Exception $e) {
            return $e->getMessage();
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
        PromotionTranslation::where('promotion_id', $id)->delete();
        $promotion = Promotion::find($id);

        $image_path = public_path('/uploads/images/redeem/') . $promotion->image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        $promotion->delete();

        return response()->json([
            'status'   =>   true,
            'message'   =>  'Deleted Successfully!'
        ], 200);
    }

    public function validateRequest(Request $request)
    {
        return $request->validate([
            'promotion_type_id' => 'required',
            'value' => 'required',
            'qty' => $request->promotion_type_id == 1 ? 'nullable' : 'required',
            // 'images' => 'required',
            // 'start_date' => $request->promotion_type_id == 1 ? 'nullable' : 'required',
            // 'end_date' =>  $request->promotion_type_id == 1 ? 'nullable' : 'required',
            // 'flag'  => $request->promotion_type_id == 1 ? 'nullable' : 'required',
        ]);
    }

    public function generateCode()
    {
        try {
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $res = "";
            for ($i = 0; $i < 8; $i++) {
                $res .= $chars[mt_rand(0, strlen($chars) - 1)];
            }
            return $res;
        } catch (Exception $e) {
        }
    }

    public function image($images, $old_img = '')
    {
        $image_name = null;
        if (isset($images)) {
            if ($old_img != '') {
                $image_path = public_path('/uploads/images/redeem/') . $old_img;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $image = $images[0];
            $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('/uploads/images/redeem');
            $image->move($path, $filename);
            $image_name = $filename;
        }

        return $image_name;
    }
}
