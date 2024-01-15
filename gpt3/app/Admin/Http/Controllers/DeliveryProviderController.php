<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateDeliveryProviderRequest;
use App\Admin\Http\Requests\UpdateDeliveryProviderRequest;
use App\Admin\Repositories\DeliveryProviderRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use DataTables;

class DeliveryProviderController extends AppBaseController
{
    /** @var  DeliveryProviderRepository */
    private $deliveryProviderRepository;

    public function __construct(DeliveryProviderRepository $deliveryProviderRepo)
    {
        $this->deliveryProviderRepository = $deliveryProviderRepo;
    }

    /**
     * Display a listing of the DeliveryProvider.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $deliveryProviders = $this->deliveryProviderRepository->all();

        if ($request->ajax()) {
            return Datatables::of($deliveryProviders)
                // ->addColumn('shop', function(Order $order){
                //     return Shop::where('id', $order->shop_id)->pluck('name')->toArray();
                // })
                ->addColumn('action', function($data){
                    return '<div class="btn-group">
                                <a href='.route("deliveryProviders.show", [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href='.route('deliveryProviders.edit', [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="'.$data->id.'" class="btn btn-danger btn-xs" id="deleteOrder"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }


        return view('admin::delivery_providers.index')
            ->with('deliveryProviders', $deliveryProviders);
    }

    /**
     * Show the form for creating a new DeliveryProvider.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::delivery_providers.create');
    }

    /**
     * Store a newly created DeliveryProvider in storage.
     *
     * @param CreateDeliveryProviderRequest $request
     *
     * @return Response
     */
    public function store(CreateDeliveryProviderRequest $request)
    {
        $input = $request->all();
        // return var_dump($input);
        $deliveryProvider = $this->deliveryProviderRepository->create($input);

        // if($request->hasFile('icon')){
        //     $image_name = icon.request()->icon->getClientOriginalExtension();
        //     $path = $request->file('icon')->move(public_path('/uploads/images/shops/deliveryProvider'), $image_name);
        //     $deliveryProvider->icon = $image_name;
        // }
        Flash::success('Delivery Provider saved successfully.');

        return redirect(route('deliveryProviders.index'));
    }

    /**
     * Display the specified DeliveryProvider.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $deliveryProvider = $this->deliveryProviderRepository->find($id);

        if (empty($deliveryProvider)) {
            Flash::error('Delivery Provider not found');

            return redirect(route('admin::deliveryProviders.index'));
        }

        return view('admin::delivery_providers.show')->with('deliveryProvider', $deliveryProvider);
    }

    /**
     * Show the form for editing the specified DeliveryProvider.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $deliveryProvider = $this->deliveryProviderRepository->find($id);

        if (empty($deliveryProvider)) {
            Flash::error('Delivery Provider not found');

            return redirect(route('admin::deliveryProviders.index'));
        }

        return view('admin::delivery_providers.edit')->with('deliveryProvider', $deliveryProvider);
    }

    /**
     * Update the specified DeliveryProvider in storage.
     *
     * @param int $id
     * @param UpdateDeliveryProviderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDeliveryProviderRequest $request)
    {
        $deliveryProvider = $this->deliveryProviderRepository->find($id);
        if (empty($deliveryProvider)) {
            Flash::error('Delivery Provider not found');

            return redirect(route('deliveryProviders.index'));
        }

        $deliveryProvider = $this->deliveryProviderRepository->update($request->all(), $id);

        Flash::success('Delivery Provider updated successfully.');

        return redirect(route('deliveryProviders.index'));
    }

    /**
     * Remove the specified DeliveryProvider from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $deliveryProvider = $this->deliveryProviderRepository->find($id);

        if (empty($deliveryProvider)) {
            Flash::error('Delivery Provider not found');

            return redirect(route('deliveryProviders.index'));
        }

        $this->deliveryProviderRepository->delete($id);

        Flash::success('Delivery Provider deleted successfully.');

        return redirect(route('deliveryProviders.index'));
    }
}
