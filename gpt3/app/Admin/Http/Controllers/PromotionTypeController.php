<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\Promotion;
use App\Admin\Models\PromotionType;
use App\Admin\Models\PromotionTypeTranslation;
use App\Admin\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class PromotionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(PromotionType::query())
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route('promotion-type.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <a type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs ml-2"><i class="glyphicon glyphicon-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin::promotions.promotion_type.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin::promotions.promotion_type.create');
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

            $promotion = PromotionType::create([
                'is_active' =>  1
            ]);

            if (isset($request->name)) {

                foreach ($request->name as $key => $value) {
                    $pro = PromotionTypeTranslation::create([
                        'promotion_type_id'  =>  $promotion->id,
                        'name'              =>  $request->name[$key] ?? $request->name[0],
                        'locale'            =>  $request->locale[$key]
                    ]);
                }

                // if ($request->ajax()) {
                //     $promotion_type = Promotion::where('id', '!=', 1)->orderBy('id', 'DESC')->get();

                //     return response()->json([
                //         'status'    =>  true,
                //         'data'      =>  $promotion_type
                //     ], 200);

                //     return $promotion_type;
                // }

                Flash::success('Promotion Type saved successfully.');
                return redirect(route('promotion-type.index'));
            }
        } catch (Exception $e) {
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
        $promotion_type = PromotionType::find($id);

        return view('admin::promotions.promotion_type.edit', compact('promotion_type'));
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

            if (isset($request->name)) {
                foreach ($request->name as $key => $value) {
                    $pro = PromotionTypeTranslation::updateOrCreate(
                        [
                            'promotion_type_id' =>  $id,
                            'locale'            =>  $request->locale[$key]
                        ],
                        [
                            'name'              =>  $request->name[$key] ?? $request->name[0],
                        ]
                    );
                }

                Flash::success('Promotion Type saved successfully.');
                return redirect(route('promotion-type.index'));
            }
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
        $promotion_type = PromotionType::find($id);
        if ($promotion_type) {
            $promotion_type->delete();
            PromotionTypeTranslation::where('promotion_type_id', $id)->delete();

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
            'name' => 'required',
        ]);
    }
}
