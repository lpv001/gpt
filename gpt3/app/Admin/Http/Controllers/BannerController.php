<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateBannerRequest;
use App\Admin\Http\Requests\UpdateBannerRequest;
use App\Admin\Repositories\BannerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\Banner;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class BannerController extends AppBaseController
{
    /** @var  BannerRepository */
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepo)
    {
        $this->path = public_path('/uploads/images/banners');
        $this->bannerRepository = $bannerRepo;
    }

    /**
     * 
     * 
     */
    public function generateThumbnails()
    {
        dd("DONE");
        $images = Banner::where('updateflag', 0)->skip(0)->take(1000)->get();
        foreach ($images as $key => $value) {
            $image_path = $this->path . '/' . $value->image;
            
            if (file_exists($image_path)) {
                createResizeImage($this->path, '/'.$value->image, '/thumbnail/' . $value->image, 970, 250);
            }
            
            Banner::where('id', $value->id)->update(['updateflag' => 1]);
        }
        
        return true;
    }

    /**
     * Display a listing of the banner.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //$this->generateThumbnails();
        
        return view('admin::banners.index');
    }

    public function datatables(Request $request)
    {
        $banners = $this->bannerRepository->all();

        if ($request->ajax()) {
            return DataTables::of($banners)
                ->addIndexColumn()
                ->editColumn('image', function ($banner) {
                    return $banner->image ? '<img src="'. asset('/uploads/images/banners/' .$banner->image) .'" alt="" srcset="" height="70">' : '<img src="https://www.villas4u.com/assets/img/image-not-found.svg" alt="" srcset="" height="70">';
                })
                ->editColumn('is_active', function ($banner) {
                    return $banner->is_active == 1 ? '<button type="button" data-id="'.$banner->id.'" data-type="active" class="btn btn-xs btn-success product-status">Enable</button>' : 
                                                    '<button type="button" data-id="'.$banner->id.'" data-type="in-active" class="btn btn-xs btn-danger product-status">Disable</button>';
                })
                ->addColumn('action', function($banner){
                    return '<div class="btn-group">
                                <a href='.route('banners.edit', [$banner->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="'.$banner->id.'" class="btn btn-danger btn-xs btn-delete"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action','image', 'is_active'])
                ->toJson();
        }
    }

    /**
     * Show the form for creating a new banner.
     *
     * @return Response
     */
    public function create()
    {

        $banners = banner::pluck('title', 'id')->toArray();

        return view('admin::banners.create', compact('banners'));
    }

    /**
     * Store a newly created banner in storage.
     *
     * @param CreatebannerRequest $request
     *
     * @return Response
     */
    public function store(CreatebannerRequest $request)
    {
        $image_name = '';
        if(isset($request->images)) 
        {
            foreach ($request->images as $image) {
                $filename = uniqid().time().'.'.$image->getClientOriginalExtension();
                $path = public_path('/uploads/images/banners');
                $image->move($path, $filename);
                $image_name = $filename;
            }
        }

        $input = [
            'title' =>  $request->title ?? '',
            'target_url' =>  $request->target_url,
            'expiry_date' =>  Carbon::parse($request->expiry_date) ?? '',
            'description' =>  $request->description ?? '',
            'image' =>  $image_name ?? '',
            'is_active' =>  $request->is_active ?? 0,
        ];

        $banner = $this->bannerRepository->create($input);

        Flash::success('banner saved successfully.');

        return redirect(route('banners.index'));
    }

    /**
     * Display the specified banner.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('banner not found');

            return redirect(route('banners.index'));
        }

        return view('admin::banners.show')->with('banner', $banner);
    }

    /**
     * Show the form for editing the specified banner.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('banner not found');

            return redirect(route('banners.index'));
        }

        $edit = 1;

        $banners = banner::pluck('title', 'id')->toArray();

        return view('admin::banners.edit', compact('banners','banner','edit'));
    }

    /**
     * Update the specified banner in storage.
     *
     * @param int $id
     * @param UpdatebannerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatebannerRequest $request)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('banner not found');

            return redirect(route('banners.index'));
        }

        $image_name = '';

        if ( isset($request->old)) {
            $image_name = $banner->image;
        } else {
            if($banner->image != "") {
                $image_path = public_path('/uploads/images/categories/'.$banner->image);
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }
        }

        if(isset($request->images)) 
        {
            foreach ($request->images as $image) {
                $filename = uniqid().time().'.'.$image->getClientOriginalExtension();
                $path = public_path('/uploads/images/banners');
                $image->move($path, $filename);
                $image_name = $filename;
            }
        }

        $input = [
            'title' =>  $request->title ?? '',
            'target_url' =>  $request->target_url,
            'expiry_date' =>  Carbon::parse($request->expiry_date) ?? '',
            'description' =>  $request->description ?? '',
            'image' =>  $image_name ?? '',
            'is_active' =>  $request->is_active ?? 0,
        ];

        $banner = $this->bannerRepository->update($input , $id);

        Flash::success('banner updated successfully.');

        return redirect(route('banners.index'));
    }

    /**
     * Remove the specified banner from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('banner not found');
            return redirect(route('banners.index'));
        }

        if($banner->image != "") {
          $image_path = public_path('/uploads/images/banners/'.$banner->image);
          if(file_exists($image_path)){
              unlink($image_path);
          }
        }

        $this->bannerRepository->delete($id);

       return response()->json(['status' => 200, 'message' => 'Banner deleted successfully!']);
    }

    public function updateStatus(Request $request)
    {
        if ($request->ajax())
        {
            Banner::where('id', $request->id)->update(['is_active' => $request->status]);
            return response()->json(['status' => 200, 'message' => 'Banner updated successfully.']);
        }
    }
}
