<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Helper\FileUploadHelper;
use App\Admin\Models\PaymentAccount;
use App\Admin\Models\PaymentMethod;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\PaymentProvider;
use App\Admin\Models\Shop;
use DataTables;
use Exception;
use Illuminate\Database\QueryException;

class PaymentAccountController extends AppBaseController
{

    private $pathView;
    private $redirectTo;
    private $paymentProviders;
    private $paymentMethods;
    private $dataTypes;
    private $shops;
    private $qrCodeImagePath;

    public function __construct()
    {
        $this->pathView = 'admin::payment_accounts';
        $this->redirectTo = 'payment-accounts';
        $this->paymentProviders = PaymentProvider::pluck('name', 'id')->toArray();
        $this->shops = Shop::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->paymentMethods = PaymentMethod::pluck('name', 'id')->toArray();
        $this->dataTypes = [
            1 => 'String',
            2 => 'Number',
            3 => 'Date',
        ];

        // 
        $this->qrCodeImagePath = public_path('uploads/images/payments/qrcodes');
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
            return Datatables::of(PaymentAccount::query())
                ->editColumn('provider_id', function ($data) {
                    $provider = PaymentProvider::find($data->provider_id);

                    return $provider ? $provider->name : 'None';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route($this->redirectTo . '.show', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href=' . route($this->redirectTo . '.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs btn-delete"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'icon'])
                ->make(true);
        }

        return view($this->pathView . '.index', [
            'routeName' => $this->redirectTo,
            'viewPath' => $this->pathView
        ]);
    }

    /**
     * Show the form for creating a new Payment.
     *
     * @return Response
     */
    public function create()
    {
        $display_fields = [];
        return view($this->pathView . '.create', [
            'routeName' => $this->redirectTo,
            'viewPath' => $this->pathView,
            'paymentProviders' => $this->paymentProviders,
            'paymentMethods' => $this->paymentMethods,
            'dataTypes' => $this->dataTypes,
            'shops' => $this->shops,
            'display_fields'    =>  $display_fields
        ]);
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
        $request->validate(PaymentAccount::rules($request->all()));

        if (count($request->field_names) < 0) {
            return \Redirect::back()->withErrors(['display_fields', 'The Display fields are required']);
        }

        $qr_code = '';
        try {
            $display_fields_json = [];

            foreach ($request->field_names as $key => $field_name) {
                $display_fields_json[] = [
                    'field_name'        =>  $field_name,
                    'field_data_type'   =>  $request->field_types[$key],
                    'note'              =>  array_key_exists($key, $request->notes) ? $request->notes[$key] : ''
                ];
            }

            $json_data = json_encode($display_fields_json, true);

            if (isset($request->qr_code)) {
                $qr_code = FileUploadHelper::uploadImage($request->qr_code, $this->qrCodeImagePath);
            }

            PaymentAccount::create([
                'provider_id'   =>  $request->provider_id,
                'method_id' => $request->method_id,
                'phone_number'  =>  $request->phone_number,
                'account_name'  =>  $request->account_name,
                'account_number'    =>  $request->account_number,
                'display_fields'    =>  $json_data,
                'qr_code'   =>  $qr_code
            ]);

            Flash::success('Payment saved successfully.');
            return redirect(route($this->redirectTo . '.index'));
        } catch (QueryException $exception) { // query exception
            // // then delete file
            FileUploadHelper::deleteFile($this->qrCodeImagePath . '/' . $qr_code);

            $errorInfo = $exception->errorInfo;
            Flash::error(end($errorInfo));
            return back()->withErrors(['errors' => end($errorInfo)])->withInput();
        } catch (Exception $e) {
            // then delete file
            FileUploadHelper::deleteFile($this->qrCodeImagePath . '/' . $qr_code);

            Flash::error($e->getMessage());
            return back()->withErrors(['errors' => $e->getMessage()])->withInput();
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
        $payment_account = PaymentAccount::with('paymentProvider')->find($id);
        if (empty($payment_account)) {
            Flash::error('Payment Account not found');

            return redirect(route($this->redirectTo . '.index'));
        }

        $display_fields = json_decode($payment_account->display_fields, true);

        return view($this->pathView . '.show', compact('payment_account', 'display_fields'));
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
        $display_fields = [];
        $payment_account = PaymentAccount::find($id);

        $display_fields = json_decode($payment_account->display_fields, true);

        if (empty($payment_account)) {
            Flash::error('Payment Account not found');

            return redirect(route($this->redirectTo . '.index'));
        }

        return view($this->pathView . '.edit', [
            'routeName' => $this->redirectTo,
            'viewPath' => $this->pathView,
            'paymentProviders' => $this->paymentProviders,
            'paymentMethods' => $this->paymentMethods,
            'dataTypes' => $this->dataTypes,
            'shops' => $this->shops,
            'payment_account' => $payment_account,
            'display_fields'    =>  $display_fields
        ]);
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
        $payment = PaymentAccount::find($id);

        if (empty($payment)) {
            Flash::error('Payment Account not found');
            return redirect(route($this->redirectTo . '.index'));
        }

        $display_fields_json = [];
        foreach ($request->field_names as $key => $field_name) {
            $display_fields_json[] = [
                'field_name'        =>  $field_name,
                'field_data_type'   =>  $request->field_types[$key],
                'note'              =>  array_key_exists($key, $request->notes) ? $request->notes[$key] : ''
            ];
        }

        $json_data = json_encode($display_fields_json, true);

        $qr_code = $payment->qr_code;
        if (isset($request->qr_code)) {
            // delete old file
            if ($qr_code != '') {
                $old = $this->qrCodeImagePath . '/' . $qr_code;
                FileUploadHelper::deleteFile($old);
            }
            $qr_code = FileUploadHelper::uploadImage($request->qr_code, $this->qrCodeImagePath);
        }

        $payment->update([
            'provider_id'   =>  $request->provider_id,
            'method_id' => $request->method_id,
            'phone_number'  =>  $request->phone_number,
            'account_name'  =>  $request->account_name,
            'account_number'    =>  $request->account_number,
            'display_fields'    =>  $json_data,
            'qr_code'   =>  $qr_code
        ]);

        Flash::success('Payment Account updated successfully.');
        return redirect(route($this->redirectTo . '.index'));
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

        $payment = PaymentAccount::find($id);

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
