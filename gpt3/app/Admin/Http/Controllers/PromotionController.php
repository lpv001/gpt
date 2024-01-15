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

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            return DataTables::of(Promotion::whereNotIn('promotion_type_id', [0, 1])->get())
                ->editColumn('is_active', function ($data) {
                    return $data->is_active == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">X</span>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route('promotion.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <a type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs ml-2"><i class="glyphicon glyphicon-trash"></i></a>';
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }

        return view('admin::promotions.promotion.index');
    }

    public function getMemberShipDiscount()
    {
        $price_retailer = Promotion::where(['promotion_type_id' => 1, 'code'    =>  'price_retailer'])->first();
        $price_wholesaler = Promotion::where(['promotion_type_id' => 1, 'code'  =>  'price_wholesaler'])->first();
        $price_distributor = Promotion::where(['promotion_type_id' => 1, 'code' =>  'price_distributor'])->first();

        return view(
            'admin::promotions.membership.index',
            compact('price_retailer', 'price_wholesaler', 'price_distributor')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promotion_type = PromotionTypeTranslation::where('locale', \App::getLocale())
            ->where('promotion_type_id', '!=', 1)->pluck('name', 'promotion_type_id');
        // return $promotion_type;
        return view('admin::promotions.promotion.create', compact('promotion_type'));
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
            if ($request->promotion_type_id == 1) {
                $price_settings = [1 => 'price_retailer', 2 => 'price_wholesaler', 3 => 'price_distributor'];
                foreach ($request->value as $key => $value) {

                    if ($value > 100) {
                        return response()->json(['status' => false, 'message' => 'Discount must be less than 100!']);
                    }

                    $key++;
                    Promotion::updateOrCreate(
                        [
                            'code'                  =>  $price_settings[$key],
                            'promotion_type_id'     =>  $request->promotion_type_id,
                        ],
                        [
                            'value'         => $value,
                            'qty'           =>  1,
                        ]
                    );
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Save Successfully',
                ], 201);
            } else {

                $promotion = Promotion::create([
                    'code'  =>  $request->code,
                    'promotion_type_id' =>  $request->promotion_type_id,
                    'value' =>  $request->value,
                    'qty'   =>  $request->qty,
                    'start_date'    =>  $request->start_date,
                    'end_date' =>  $request->end_date,
                    'is_active' =>  1,
                    'flag'  =>  $request->discount_flag ?? 1,
                ]);

                if (isset($request->name)) {
                    foreach ($request->name as $key => $value) {
                        PromotionTranslation::create([
                            'promotion_id'  =>  $promotion->id,
                            'name'  =>  $value ?? $request->name[$key],
                            'description'  =>  $value ?? $request->name[$key],
                            'locale'    =>  $request->locale[$key]
                        ]);
                    }
                }

                Flash::success('Promotion saved successfully.');
                return redirect(route('promotion.index'));
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => 'Something errors :' . $e->getMessage()], 200);
            }
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
        //
        $promotion = Promotion::find($id);
        $promotion_type = PromotionTypeTranslation::where('locale', \App::getLocale())
            ->where('promotion_type_id', '!=', 1)->pluck('name', 'promotion_type_id');

        // return $promotion;
        return view('admin::promotions.promotion.edit', compact('promotion', 'promotion_type'));
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

            $promotion = Promotion::find($id)->update([
                'code'  =>  $request->code,
                'promotion_type_id' =>  $request->promotion_type_id,
                'value' =>  $request->value,
                'qty'   =>  $request->qty,
                'start_date'    =>  $request->start_date,
                'end_date' =>  $request->end_date,
                'is_active' =>  1,
                'flag'  =>  $request->discount_flag ?? 1,
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

            Flash::success('Promotion updated successfully.');
            return redirect(route('promotion.index'));
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
        $promotion_type = Promotion::find($id)->delete();
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
            'start_date' => $request->promotion_type_id == 1 ? 'nullable' : 'required',
            'end_date' =>  $request->promotion_type_id == 1 ? 'nullable' : 'required',
            // 'flag'  => $request->promotion_type_id == 1 ? 'nullable' : 'required',
        ]);
    }

    public function generateCode(Request $request)
    {
        try {
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $res = "";
            for ($i = 0; $i < 8; $i++) {
                $res .= $chars[mt_rand(0, strlen($chars) - 1)];
            }
            return response()->json(['status' => true, 'code' => $res]);
        } catch (Exception $e) {
            return response()->json(['status' => true, 'message' => 'Cant generate code due internal error']);
        }
    }
}
