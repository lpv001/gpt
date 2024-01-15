<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\District;
use App\Admin\Models\Membership;
use App\Admin\Models\Shop;
use App\Admin\Models\ShopCategory;
use App\Admin\Models\ShopCategoryTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Yajra\DataTables\DataTables;

class ShopCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(ShopCategory::query())
                ->editColumn('image_name', function ($data) {
                    return '<img alt="image" src="' . asset($data->image_name) . '" class="img-thumbnail" width="40%" height="40%">';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route('shop-category.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs btn-delete"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'image_name'])
                ->make(true);
        }

        return view('admin::shop_categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin::shop_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try {
            $filename = '';
            if ($request->hasFile('images')) {
                if (!is_null($request->images[0])) {
                    $filename = uniqid() . time() . '.' . $request->images[0]->getClientOriginalExtension();
                    $path = public_path('/uploads/images/shops/categories');
                    $request->images[0]->move($path, $filename);
                }
            }

            $shop_category = ShopCategory::create([
                'image_name'   =>  $filename,
            ]);

            foreach ($request->locale as $key => $locale) {
                ShopCategoryTranslation::create([
                    'shop_category_id'  =>  $shop_category->id,
                    'name'              =>  $request->name[$key] ?? $request->name[0],
                    'locale'            =>  $locale
                ]);
            }

            if ($request->ajax()) {
                $shop_categories = ShopCategoryTranslation::where('locale', \App::getLocale())
                    ->orderBy('shop_category_id', 'DESC')->select('shop_category_id', 'name')->get();
                return response()->json(['status' => true, 'data' => $shop_categories], 201);
            }

            return redirect()->route('shop-category.index')->with(['success', 'Shop Category Create Successfully']);
        } catch (Exception $e) {
            return \Redirect::back()->withErrors(['error', $e->getMessage()])->withInput();
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
        $shop_category = ShopCategory::find($id);
        $shop_category_translate = ShopCategoryTranslation::where('shop_category_id', $id)->get();

        return view('admin::shop_categories.edit', compact('shop_category', 'shop_category_translate'));
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
        // dd($request->all());
        $request->validate([
            'name' => 'required',
        ]);

        try {
            $shop_category = ShopCategory::find($id);

            $filename = '';
            if ($request->hasFile('images')) {
                if (file_exists(public_path() . $shop_category->image_name))
                    unlink(public_path() . $shop_category->image_name);

                if (!is_null($request->images[0])) {
                    $filename = uniqid() . time() . '.' . $request->images[0]->getClientOriginalExtension();
                    $path = public_path('/uploads/images/shops/categories');
                    $request->images[0]->move($path, $filename);
                }
            }

            $shop_category->update([
                'image_name'   =>  $filename,
            ]);

            foreach ($request->locale as $key => $locale) {
                ShopCategoryTranslation::updateOrCreate([
                    'shop_category_id'  =>  $id,
                    'locale'            =>  $locale

                ], [
                    'name'              =>  $request->name[$key] ?? $request->name[0],
                ]);
            }

            return redirect()->route('shop-category.index')->with(['success', 'Shop Category Create Successfully']);
        } catch (Exception $e) {
            return \Redirect::back()->withErrors(['error', $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //
        ShopCategory::find($id)->delete();
        ShopCategoryTranslation::where('shop_category_id', $id)->delete();

        if ($request->ajax()) {
            return response()->json(['status' => true, 'Shop Category Deleted'], 200);
        }
    }
}
