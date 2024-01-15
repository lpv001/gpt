<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Helper\FileUploadHelper;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\PaymentProvider;
use DataTables;
use Exception;

class PaymentProviderController extends AppBaseController
{
    /** @var  PaymentRepository */
    private $paymentRepository;
    private $iconPath;
    private $icon;


    public function __construct()
    {
        $this->iconPath = public_path('/uploads/images/payments/icons');
        $this->icon = '';
    }

    /**
     * Display a listing of the Payment.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(PaymentProvider::query())
                ->editColumn('icon', function ($data) {
                    return '<img src="' .  $data->icon . '" class="text-center" width="80" height="80">';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route("payment-provider.show", [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href=' . route('payment-provider.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs btn-delete"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'icon'])
                ->make(true);
        }

        return view('admin::payment_providers.index');
    }

    /**
     * Show the form for creating a new Payment.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::payment_providers.create');
    }

    /**
     * Store a newly created Payment in storage.
     *
     * @param CreatePaymentRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  =>  'required',
            'description'  =>  'required',
            'images'  =>  'required',
        ]);

        try {

            if (isset($request->images)) {
                foreach ($request->images as $image) {
                    $request->icon = FileUploadHelper::uploadImage($image, $this->iconPath);
                }
            }

            $data = PaymentProvider::create([
                'name'  =>  $request->name,
                'description'  =>  $request->description,
                'icon'  =>  $request->icon,
            ]);


            Flash::success('Payment saved successfully.');
            return redirect(route('payment-provider.index'));
        } catch (Exception $e) {
            Flash::error($e->getMessage());
            return back();
        }
    }

    /**
     * Display the specified Payment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payment = PaymentProvider::find($id);
        if (empty($payment)) {
            Flash::error('Payment Provider not found');

            return redirect(route('payment-provider.index'));
        }

        return view('admin::payment_providers.show')->with('payment', $payment);
    }

    /**
     * Show the form for editing the specified Payment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payment = PaymentProvider::find($id);

        if (empty($payment)) {
            Flash::error('Payment not found');

            return redirect(route('payments.index'));
        }

        return view('admin::payment_providers.edit')->with('payment', $payment);
    }

    /**
     * Update the specified Payment in storage.
     *
     * @param int $id
     * @param UpdatePaymentRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $payment = PaymentProvider::find($id);

        if (empty($payment)) {
            Flash::error('Payment not found');

            return redirect(route('payments.index'));
        }

        $icon = '';
        $old_icon = explode('/', $payment->icon);

        if (isset($request->images)) {
            // upload new icon image
            $request->icon = FileUploadHelper::uploadImage($request->images[0], $this->iconPath);
            // delete old image
            $old_icons = $this->iconPath . '/' . end($old_icon);
            FileUploadHelper::deleteFile($old_icons);
        } else {
            $request->icon = end($old_icon);
        }

        $payment->update([
            'name'  =>  $request->name,
            'description'  =>  $request->description,
            'icon'  =>  $request->icon,
        ]);

        Flash::success('Payment Provider updated successfully.');

        return redirect(route('payment-provider.index'));
    }

    /**
     * Remove the specified Payment from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {

        $payment = PaymentProvider::find($id);

        if (empty($payment)) {
            return redirect(route('payments.index'));

            return response()->json([
                'status'  => true,
                'message' => 'Not Found!',
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Delete Successfully',
        ], 200);
    }
}
