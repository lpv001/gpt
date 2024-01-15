<?php

namespace App\Http\Controllers\API;

use App\DeliveryAddress;
use App\Helper\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DeliveryAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      try {
        $addresses = DeliveryAddress::where(['user_id' => auth()->user()->id, 'is_active' => 1])->get();
        return HttpResponse::response(HttpResponse::$created, 'Get Delivery Addresses Success.', $addresses);
      } catch (Exception $e) {
        return HttpResponse::response(HttpResponse::$serverError, $e->getMessage(), []);
      }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'address' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return HttpResponse::response(HttpResponse::$forbidden, $validator->messages()->first(), []);
        }

        try {
            $deliverAddress = DeliveryAddress::create([
                'user_id' => auth()->user()->id,
                'address' => $request->address,
                'lat'   =>  $request->lat,
                'lng'   =>  $request->lng,
                'phone'   =>  $request->phone ?? auth()->user()->phone,
                'tag'   =>  $request->tag,
            ]);

            return HttpResponse::response(HttpResponse::$created, 'Delilvery Address Created', $deliverAddress);
        } catch (Exception $e) {
            return HttpResponse::response(HttpResponse::$serverError, $e->getMessage(), []);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $deliverAddress = DeliveryAddress::find($id);

        if (!$deliverAddress)
            return HttpResponse::response(HttpResponse::$notfound, 'Cant find Delivery address.', []);

        $deliverAddress->update(['is_active' => 0]);
        return HttpResponse::response(HttpResponse::$ok, 'Deleted Successfully', []);
    }
}
