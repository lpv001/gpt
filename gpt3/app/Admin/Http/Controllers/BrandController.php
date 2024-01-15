<?php

namespace App\Admin\Http\Controllers;


use App\Admin\Repositories\BrandRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\Brand;
use App\Admin\Models\BrandTranslation;
use Yajra\DataTables\DataTables;

class BrandController extends AppBaseController
{
    /** @var  BrandRepository */
    private $brandRepository;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->path = public_path('/uploads/images/brands');
        $this->brandRepository = $brandRepo;
    }

    /**
     * 
     * 
     */
    public function generateThumbnails()
    {
        dd("DONE");
        $images = Brand::where('updateflag', 0)->skip(0)->take(1000)->get();
        
        foreach ($images as $key => $value) {
            $image_path = $this->path . '/' . $value->image_name;
            if (strlen($value->image_name) > 0) {
                if (file_exists($image_path)) {
                    createThumbnail($this->path, $value->image_name);
                }
            }
            Brand::where('id', $value->id)->update(['updateflag' => 1]);
        }
        
        return true;
    }

    /**
     * Display a listing of the Brand.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //$this->generateThumbnails();
        
        return view('admin::brands.index');
    }

    public function datatables(Request $request)
    {
        $brands = $this->brandRepository->all();

        if ($request->ajax()) {
            return DataTables::of($brands)
                ->addIndexColumn()
                ->editColumn('image_name', function ($brand) {
                    return $brand->image_name ? '<img src="' . asset('/uploads/images/brands/thumbnail/small_' . $brand->image_name) . '" alt="" srcset="" height="70">' : '<img src="https://www.villas4u.com/assets/img/image-not-found.svg" alt="" srcset="" height="70">';
                })
                ->editColumn('is_active', function ($brand) {
                    return $brand->is_active == 1 ? '<button type="button" data-id="' . $brand->id . '" data-type="active" class="btn btn-xs btn-success product-status">Enable</button>' :
                        '<button type="button" data-id="' . $brand->id . '" data-type="in-active" class="btn btn-xs btn-danger product-status">Disable</button>';
                })
                ->addColumn('action', function ($brand) {
                    return '<div class="btn-group">
                                <a href=' . route('brands.edit', [$brand->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $brand->id . '" class="btn btn-danger btn-xs btn-delete"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'image_name', 'is_active'])
                ->toJson();
        }
    }

    /**
     * Show the form for creating a new Brand.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::brands.create');
    }

    /**
     * Store a newly created Brand in storage.
     *
     * @param CreateBrandRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // return dd($request->all());
        $request->validate([
            'name' => 'required',
            // 'slug' => 'required',
        ]);

        $image_name = '';
        if (isset($request->images)) {
            foreach ($request->images as $image) {
                $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('/uploads/images/brands');
                $image->move($path, $filename);
                createThumbnail($this->path, $filename);
                $image_name = $filename;
            }
        }
        $input = [
            'name' =>  $request->name[0] ?? '',
            'slug' =>  $request->slug ?? strtolower($request->name[0]),
            'image_name' =>  $image_name,
            'is_active' =>  $request->is_active ?? 0,
        ];

        $brand = $this->brandRepository->create($input);


        $_name[0] = ['local' => 'en', 'name' => ''];
        $_name[1] = ['local' => 'km', 'name' => ''];
        $_name[2] = ['local' => 'cn', 'name' => ''];
        foreach ($request->lang as $item_key => $item) {
            if ($item === 'en') $_name[0]['name'] = $request->name[$item_key];
            if ($item === 'km') $_name[1]['name'] = $request->name[$item_key];
            if ($item === 'cn') $_name[2]['name'] = $request->name[$item_key];
        }

        $lang = ['en', 'km', 'cn'];
        foreach ($lang as $key => $value) {
            BrandTranslation::create([
                'brand_id'   =>  $brand->id,
                'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
                'locale'     =>  $value,
            ]);
        }

        Flash::success('Brand saved successfully.');

        return redirect(route('brands.index'));
    }

    /**
     * Display the specified Brand.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $brand = $this->brandRepository->find($id);

        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        return view('admin::brands.show')->with('brand', $brand);
    }

    /**
     * Show the form for editing the specified Brand.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $brand = $this->brandRepository->find($id);
        $brand_tranlsation = BrandTranslation::where('brand_id', $id)->pluck('name', 'locale');
        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        $edit = 1;
        return view('admin::brands.edit', compact('brand', 'edit', 'brand_tranlsation'));
    }

    /**
     * Update the specified Brand in storage.
     *
     * @param int $id
     * @param UpdateBrandRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'slug' => 'required',
        ]);
        $banner = $this->brandRepository->find($id);

        $image_name = '';

        if (isset($request->old)) {
            $image_name = $banner->image_name;
        } else {
            if ($banner->image_name != "") {
                $image_path = public_path('/uploads/images/brands/' . $banner->image_name);
                if (file_exists($image_path)) {
                    unlink($image_path);
                    deleteThumbnail($this->path, $value->image_name);
                }
            }
        }

        if (isset($request->images)) {
            foreach ($request->images as $image) {
                $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('/uploads/images/brands');
                $image->move($path, $filename);
                createThumbnail($this->path, $filename);
                $image_name = $filename;
            }
        }

        $input = [
            'name' =>  $request->name[0] ?? '',
            'slug' =>  $request->slug ?? strtolower($request->name[0]),
            'image_name' =>  $image_name ?? '',
            'is_active' =>  $request->is_active ?? 0,
        ];

        $brand = $this->brandRepository->update($input, $id);
        // return dd($request->all());

        $_name[0] = ['local' => 'en', 'name' => ''];
        $_name[1] = ['local' => 'km', 'name' => ''];
        $_name[2] = ['local' => 'cn', 'name' => ''];
        foreach ($request->lang as $item_key => $item) {
            if ($item === 'en') $_name[0]['name'] = $request->name[$item_key];
            if ($item === 'km') $_name[1]['name'] = $request->name[$item_key];
            if ($item === 'cn') $_name[2]['name'] = $request->name[$item_key];
        }

        $lang = ['en', 'km', 'cn'];
        foreach ($lang as $key => $value) {
            BrandTranslation::updateOrCreate([
                'brand_id'  =>  $id,
                'locale'    =>  $value
            ], [
                'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
            ]);
            // BrandTranslation::where('brand_id', $id)->where('locale', $value)->update([
            //     'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
            // ]);
        }

        Flash::success('Brand updated successfully.');

        return redirect(route('brands.index'));
    }

    /**
     * Remove the specified Brand from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $brand = $this->brandRepository->find($id);

        if (empty($brand)) {
            Flash::error('Brand not found');

            return redirect(route('brands.index'));
        }

        if ($brand->image_name != "") {
            $image_path = public_path('/uploads/images/brands/' . $brand->image_name);
            if (file_exists($image_path)) {
                unlink($image_path);
                deleteThumbnail($this->path, $value->image_name);
            }
        }

        \DB::table('brand_translations')->where('brand_id', $id)->delete();
        $this->brandRepository->delete($id);

        return response()->json(['status'   => 200, 'message'   => 'Brand deleted sucessfully!']);
    }

    public function updateStatus(Request $request)
    {
        if ($request->ajax()) {
            Brand::where('id', $request->id)->update(['is_active' => $request->status]);
            return response()->json(['status' => 200, 'message' => 'Brand updated successfully.']);
        }
    }
}
