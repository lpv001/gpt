<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateDeliveryRequest;
use App\Admin\Http\Requests\UpdateDeliveryRequest;
use App\Admin\Repositories\DeliveryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\Shop;
use App\Admin\Models\City;
use App\Admin\Models\DeliveryProvider;
use DataTables;
use DB;

class DeliveryController extends AppBaseController
{
    /** @var  DeliveryRepository */
    private $deliveryRepository;
    private $city_list;

    public function __construct(DeliveryRepository $deliveryRepo)
    {
        $this->deliveryRepository = $deliveryRepo;
        
        $cities = DB::table('city_provinces')->select('id', 'default_name')->get();
        $this->city_list = [0 => 'Any City'];
        foreach ($cities as $city) {
          $this->city_list[$city->id] = $city->default_name;
        }
    }

    /**
     * Display a listing of the Delivery.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $deliveries = DB::table('deliveries as d')
                 ->join('city_provinces as cp', 'cp.id', '=', 'd.city_id1')
                 ->join('delivery_providers as dp', 'dp.id', '=', 'd.provider_id')
                 ->select([
                    'd.id', 
                    'd.name', 
                    'dp.name as provider', 
                    'cp.default_name as from_city', 
                    'd.min_distance', 
                    'd.max_distance', 
                    'd.cost',
                    'd.city_id2']);
        
        if ($request->ajax()) {
            return Datatables::of($deliveries)
                ->addColumn('to_city', function($data){
                   return $this->city_list[$data->city_id2];
                })
                ->addColumn('action', function($data){
                    return '<div class="btn-group">
                                <a href='.route("deliveries.show", [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href='.route('deliveries.edit', [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="'.$data->id.'" class="btn btn-danger btn-xs" id="deletedeliverie"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin::deliveries.index')
            ->with('deliveries', $deliveries);
    }

    /**
     * Show the form for creating a new Delivery.
     *
     * @return Response
     */
    public function create()
    {
        //$shop = Shop::pluck('name', 'id')->toArray();
        $cities = City::pluck('default_name', 'id')->toArray();
        $deliveryProvider = DeliveryProvider::pluck('name', 'id')->toArray();

        return view('admin::deliveries.create', compact('cities','deliveryProvider'));
    }

    /**
     * Store a newly created Delivery in storage.
     *
     * @param CreateDeliveryRequest $request
     *
     * @return Response
     */
    public function store(CreateDeliveryRequest $request)
    {
        $input = $request->all();
        if (!$input['city_id2']) {
          $input['city_id2'] = 0;
        }
        
        $delivery = $this->deliveryRepository->create($input);
        Flash::success('Delivery saved successfully.');
        return redirect(route('deliveries.index'));
    }

    /**
     * Display the specified Delivery.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $delivery = $this->deliveryRepository->find($id);

        if (empty($delivery)) {
            Flash::error('Delivery not found');

            return redirect(route('deliveries.index'));
        }

        return view('admin::deliveries.show')->with('delivery', $delivery);
    }

    /**
     * Show the form for editing the specified Delivery.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $delivery = $this->deliveryRepository->find($id);
        //$shop = Shop::pluck('name', 'id')->toArray();
        $cities = City::pluck('default_name', 'id')->toArray();
        $deliveryProvider = DeliveryProvider::pluck('name', 'id')->toArray();
        $edit = 1;

        if (empty($delivery)) {
            Flash::error('Delivery not found');

            return redirect(route('deliveries.index'));
        }

        return view('admin::deliveries.edit', compact('delivery', 'cities', 'deliveryProvider','edit'));
    }

    /**
     * Update the specified Delivery in storage.
     *
     * @param int $id
     * @param UpdateDeliveryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDeliveryRequest $request)
    {
        $delivery = $this->deliveryRepository->find($id);
        
        if (empty($delivery)) {
            Flash::error('Delivery not found');
            return redirect(route('deliveries.index'));
        }
        
        $inputs = $request->all();
        $inputs['city_id2'] = $inputs['city_id2'] ?? 3;
        $delivery = $this->deliveryRepository->update($inputs, $id);
        
        Flash::success('Delivery updated successfully.');
        return redirect(route('deliveries.index'));
    }

    /**
     * Remove the specified Delivery from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $delivery = $this->deliveryRepository->find($id);

        if (empty($delivery)) {
            Flash::error('Delivery not found');

            return redirect(route('deliveries.index'));
        }

        $this->deliveryRepository->delete($id);

        Flash::success('Delivery deleted successfully.');

        return redirect(route('deliveries.index'));
    }
}
