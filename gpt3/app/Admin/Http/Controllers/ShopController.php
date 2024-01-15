<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateShopRequest;
use App\Admin\Http\Requests\UpdateShopRequest;
use App\Admin\Repositories\ShopRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Redirect;
use function GuzzleHttp\Promise\all;
use Exception;
use Flash;
use Response;
use DataTables;

use App\Admin\Models\User;
use App\Admin\Models\Shop;
use App\Admin\Models\City;
use App\Admin\Models\CountryModel;
use App\Admin\Models\District;
use App\Admin\Models\Membership;
use App\Admin\Models\ShopCategory;
use App\Admin\Models\ShopCategoryTranslation;

class ShopController extends AppBaseController
{
    /** @var  ShopRepository */
    private $shopRepository;

    public function __construct(ShopRepository $shopRepo)
    {
        $this->path = public_path('/uploads/images/shops');
        $this->shopRepository = $shopRepo;
    }

    /**
     * 
     * 
     */
    public function generateThumbnails()
    {
        $images = Shop::where('updateflag', 0)->skip(0)->take(1000)->get();
        if (count($images) < 1) {
            dd("DONE");
        }
        
        foreach ($images as $key => $value) {
            $image_path = $this->path . '/' . $value->image_name;
            if (strlen($value->image_name) > 0) {
                if (file_exists($image_path)) {
                    createThumbnail($this->path, $value->image_name);
                }
            }
            Shop::where('id', $value->id)->update(['updateflag' => 1]);
        }
        
        return true;
    }

    /**
     * Display a listing of the Shop.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
      $shop = new Shop;
      $countTotal = $shop->countTotal();
      $countCreatedToday = $shop->countCreatedToday();
      
      $shops = Shop::where(function ($query) use ($request) {
              if ($request->shop_id) {
                $query->where('id', $request->shop_id);
              }
              if ($request->owner) {
                $query->where('user_id', $request->owner);
              }
              if ($request->status) {
                $query->where('status', $request->status);
              }
              if ($request->shop_category_id) {
                $query->where('shop_category_id', $request->shop_category_id);
              }
              if ($request->name) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
              }
              })
              ->select([
                      'id',
                      'user_id', 
                      'supplier_id',
                      'name',
                      'about',
                      'logo_image',
                      'cover_image',
                      'phone',
                      'country_id',
                      'city_province_id',
                      'district_id',
                      'address',
                      'lat',
                      'lng',
                      'membership_id',
                      'is_active',
                      'status',
                      'shop_category_id'])
              ->orderBy('id', 'DESC')
              ->paginate(10);
      
      $shop_categories = DB::table('shop_category_translations')->where('locale', \App::getLocale())->pluck('name', 'shop_category_id')->toArray();
      $owners = DB::table('users')->pluck('full_name', 'id')->toArray();
      
      return view('admin::shops.index', compact('shops', 'shop_categories', 'owners', 'countCreatedToday', 'countTotal'));
    }

    /**
     * Display a listing of the Shop.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexOld(Request $request)
    {
        $shops = $this->shopRepository->paginate(20);
        // $user = User::where('id', 1)->first()->full_name;
        // return $user;
        if ($request->ajax()) {
            return Datatables::of(Shop::query())
                ->addColumn('username', function (Shop $shop) {
                    $name = getUserName($shop->user_id) ? getUserName($shop->user_id)->full_name : 'N/A';
                    return '<a href=' . route("users.show", [$shop->user_id]) . '>' . $name . '</a>';
                })
                ->addColumn('supplier', function (Shop $shop) {
                    $name = getSupplier($shop->supplier_id) ? getSupplier($shop->supplier_id)->name : 'N/A';
                    return '<a href=' . route("shops.show", [$shop->supplier_id]) . '>' . $name . '</a>';
                })
                ->addColumn('city', function (Shop $shop) {
                    return City::where('id', $shop->city_province_id)->pluck('default_name')->toArray();
                })
                ->addColumn('district', function (Shop $shop) {
                    return District::where('id', $shop->district_id)->pluck('default_name')->toArray();
                })
                ->addColumn('membership', function (Shop $shop) {
                    return '<span class="text-capitalize">' . Membership::where('id', $shop->membership_id)->first()->name . '</span>';
                })
                ->addColumn('status', function (Shop $shop) {
                    if ($shop->status == 1) {
                        return '<span class="label label-success">Accepted</span>';
                    } else if ($shop->status == 10) {
                        return '<span class="label label-danger">Rejected</span>';
                    } else {
                        return '<span class="label label-warning">Pending</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                    <a href=' . route("shops.show", [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                    <a href=' . route('shops.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                    <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs" id="deleteShop"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'username', 'supplier', 'status', 'membership'])
                ->make(true);
        }

        return view('admin::shops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new Shop.
     *
     * @return Response
     */
    public function create()
    {
        $locale = \App::getLocale();
        $shops = Shop::pluck('name', 'id')->toArray();
        $shop = Shop::pluck('name', 'id')->toArray();
        $countries = CountryModel::find(1)->pluck('default_name', 'id')->toArray();
        $cities = City::pluck('default_name', 'id')->toArray();
        $districts = District::pluck('default_name', 'id')->toArray();
        $status = [0 => 'Pending', 10 => 'Reject', 1 => 'Accept'];
        $membership = Membership::pluck('name', 'id')->toArray();
        $users = User::pluck('full_name', 'id');
        $shop_category = ShopCategoryTranslation::where('locale', $locale)->pluck('name', 'id');

        return view('admin::shops.create', compact(
            'countries',
            'cities',
            'districts',
            'shops',
            'shop',
            'status',
            'membership',
            'users',
            'shop_category'
        ));
    }

    /**
     * Store a newly created Shop in storage.
     *
     * @param CreateShopRequest $request
     *
     * @return Response
     */
    public function store(CreateShopRequest $request)
    {
        try {
            $input = $request->all();
            $input['user_id'] = $input['user_ids'][0];
            
            // Check if the user already has shop
            /*
            $chkshop = Shop::where('user_id', $input['user_id'])->first();
            if ($chkshop != null) {
              return \Redirect::back()->withErrors('Shop already exist')->withInput($request->all());
            }
            */
            
            if(!isset($input['shop_category_id'])) {
                $input['shop_category_id'] = 0;
            }
            $shop = $this->shopRepository->create($input);
            
            // Save images
            if ($request->hasFile('logo_image')) {
              $filename = 'logo' . uniqid() . time() . '.' . request()->logo_image->getClientOriginalExtension();
              $path = $request->file('logo_image')->move(public_path('/uploads/images/shops'), $filename);
              $shop->logo_image = $filename;
            }
            if ($request->hasFile('cover_image')) {
              $filename = 'cover' . uniqid() . time() . '.' . request()->cover_image->getClientOriginalExtension();
              $path = $request->file('cover_image')->move(public_path('/uploads/images/shops'), $filename);
              $shop->cover_image = $filename;
            }
            $shop->update();
            
            // Save shop user
            $user = User::find($request->user_ids);
            $shop->users()->attach($user);
            
            Flash::success('Shop saved successfully.');
            return redirect(route('shops.index'));
        } catch (Exception $e) {
            return $e->getMessage();
            Flash::error($e->getMessage());
            return \Redirect::back()->withErrors(['error', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Store a newly created Shop in storage.
     *
     * @param CreateShopRequest $request
     *
     * @return Response
     */
    public function storeOld(CreateShopRequest $request)
    {
        try {
            $input = $request->all();
            $input['user_id'] = $input['user_ids'][0];
            if(!isset($input['shop_category_id'])) {
                $input['shop_category_id'] = 0;
            }
            $shop = $this->shopRepository->create($input);
            
            // Save shop user
            $user = User::find($request->user_ids);
            $shop->users()->attach($user);
            
            Flash::success('Shop saved successfully.');
            return redirect(route('shops.index'));
        } catch (Exception $e) {
            return $e->getMessage();
            Flash::error($e->getMessage());
            return \Redirect::back()->withErrors(['error', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Display the specified Shop.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $shop = $this->shopRepository->find($id);
        $my_suppliers = Shop::where('id', '!=', $id)->where('supplier_id', $id)->get();
        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        return view('admin::shops.show', compact('shop', 'my_suppliers'));
    }

    /**
     * Show the form for editing the specified Shop.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $shop = $this->shopRepository->find($id);
        if (empty($shop)) {
            Flash::error('Shop not found');
            return redirect(route('shops.index'));
        }

        $uids = array();
        foreach ($shop->users as $u) {
            $uids[] = $u->id;
        }
        if (count($uids) > 0) {
            $shop->user_ids = $uids;
        }

        $locale = \App::getLocale();
        $shops = Shop::pluck('name', 'id')->toArray();
        $countries = [1 => 'Cambodia', 2 => 'China'];
        $cities = City::pluck('default_name', 'id')->toArray();
        $districts = District::pluck('default_name', 'id')->toArray();
        $status = [0 => 'Pending', 10 => 'Reject', 1 => 'Accept'];
        $membership = Membership::pluck('name', 'id')->toArray();
        $users = User::pluck('full_name', 'id');
        $edit = 1;
        $shop_category = ShopCategoryTranslation::where('locale', $locale)->pluck('name', 'id');

        return view('admin::shops.edit', compact(
            'countries',
            'cities',
            'districts',
            'shops',
            'shop',
            'edit',
            'status',
            'membership',
            'users',
            'shop_category'
        ));
    }

    /**
     * Update the specified Shop in storage.
     *
     * @param int $id
     * @param UpdateShopRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateShopRequest $request)
    {
        $lat_pattern  = '/\A[+-]?(?:90(?:\.0{1,18})?|\d(?(?<=9)|\d?)\.\d{1,18})\z/x';
        $long_pattern = '/\A[+-]?(?:180(?:\.0{1,18})?|(?:1[0-7]\d|\d{1,2})\.\d{1,18})\z/x';

        if (!(preg_match($lat_pattern, $request->lat))) {
            return back()->withInput($request->all())->withErrors('Latitude is invalid');
        }
        if (!(preg_match($long_pattern, $request->lng))) {
            return back()->withInput($request->all())->withErrors('Longitude is invalid');
        }

        $shop = $this->shopRepository->find($id);
        if (empty($shop)) {
            Flash::error('Shop not found');
            return redirect(route('shops.index'));
        }

        // Updating shop user
        $shop->users()->sync($request->input('user_ids', []));

        $input = $request->all();
        $input['user_id'] = $input['user_ids'][0];
        if(!isset($input['shop_category_id'])) {
            $input['shop_category_id'] = 0;
        }
        
        // Old Images
        if (isset($request->logo_image_delete)) {
          $image_file = $this->path . '/' . $shop->logo_image;
          if (file_exists($image_file)) {
              unlink($image_file);
              $input['logo_image'] = null;
          }
        }
        if (isset($request->cover_image_delete)) {
          $image_file = $this->path . '/' . $shop->cover_image;
          if (file_exists($image_file)) {
              unlink($image_file);
              $input['cover_image'] = null;
          }
        }
        
        // Replace new product images
        if ($request->hasFile('logo_image')) {
          $filename = 'logo' . uniqid() . time() . '.' . request()->logo_image->getClientOriginalExtension();
          $path = $request->file('logo_image')->move(public_path('/uploads/images/shops'), $filename);
          $input['logo_image'] = $filename;
        }
        if ($request->hasFile('cover_image')) {
          $filename = 'cover' . uniqid() . time() . '.' . request()->cover_image->getClientOriginalExtension();
          $path = $request->file('cover_image')->move(public_path('/uploads/images/shops'), $filename);
          $input['cover_image'] = $filename;
        }
        
        // Prepare to send notification to shop owner on accept or any updates
        
        $shop = $this->shopRepository->update($input, $id);
        
        if ($request->status == 1) {
            $shop = $this->shopRepository->update(['is_active' => 1], $id);
            User::where("id", $shop->user_id)->update([
                'shop_id' => $shop->id,
                'membership_id' => $shop->membership_id,
                'supplier_id' => $shop->supplier_id
            ]);
        }
        
        Flash::success('Shop updated successfully.');
        return redirect(route('shops.index'));
    }

    /**
     * Remove the specified Shop from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
      $shop = $this->shopRepository->find($id);
      
      if (empty($shop)) {
          Flash::error('Shop not found');
          return redirect(route('shops.index'));
      }
      
      // Delete shop user
      $shop->users()->detach();
      
      // Delete images
      $logo_image = $this->path . '/' . $shop->logo_image;
      if (file_exists($logo_image)) {
          unlink($logo_image);
      }
      $cover_image = $this->path . '/' . $shop->cover_image;
      if (file_exists($cover_image)) {
          unlink($cover_image);
      }
                  
      $this->shopRepository->delete($id);
      
      Flash::success('Shop deleted successfully.');
      return redirect(route('shops.index'));
    }

    /**
     * Show the form of search shop .
     *
     * @return Response
     */
    public function search(Request $request, Shop $shop)
    {
        $input = $request->all();
        $membership = Membership::select('id', 'name')->get();
        $city = City::select('id', 'default_name')->get();
        $districts = District::select('id', 'default_name', 'city_province_id')->get();
        $shops = [];

        // Search for a user based on their shop.
        if ($request->search_by != null || $request->name != null || $request->date != null) {
            $shops = Shop::where(function ($query) use ($request) {
                if ($request->name) {
                    $query->where('name', 'like', '%' . $request->name . '%')
                        ->orWhere('phone', 'like', '%' . $request->name . '%');
                }

                if ($request->search_by == 'membership') {
                    if ($request->filter_value != null) {
                        $query->where('membership_id', $request->filter_value);
                    }
                }

                if ($request->search_by == 'status') {
                    if ($request->filter_value != null) {
                        $query->where('status', $request->filter_value);
                    }
                }

                if ($request->search_by == 'city') {
                    if ($request->filter_value != null) {
                        $query->where('city_province_id', $request->filter_value);
                    }
                }

                if ($request->filter_value_1 != null) {
                    $query->where('district_id', $request->filter_value_1);
                }

                if ($request->date) {
                    $query->whereDate('created_at', $request->date);
                }
            })->paginate(20);
        } else {
            $shops = Shop::paginate(20);
        }

        return view('admin::shops.index', compact('shops', 'membership', 'city', 'districts'));
    }

    public function getSupplier($id)
    {
        $supplier =  Shop::where('id', $id)->first(['city_province_id', 'district_id', 'membership_id', 'lat', 'lng']);

        if ($supplier) {
            $membership = Membership::where(function ($q) use ($supplier) {
                if ($supplier->membership_id == 4) {
                    $q->where('id', 3);
                }
                if ($supplier->membership_id == 3) {
                    $q->where('id', 2);
                }
                if ($supplier->membership_id == 2) {
                    $q->where('id', 1);
                }
                if ($supplier->membership_id == 1) {
                    $q->where('id', 5);
                }
            })->first(['name', 'id']);

            return response()->json(['status' => 200, 'data' => [
                'supplier'  =>  $supplier,
                'membership'  =>  $membership,
            ]]);
        }
    }
}
