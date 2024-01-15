<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Helper\Constant;
use App\Admin\Http\Requests\CreatePaymentMethodRequest;
use App\Admin\Http\Requests\UpdatePaymentMethodRequest;
use App\Admin\Repositories\PaymentMethodRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use DataTables;
use App\Admin\Models\PaymentMethod;
use App\Admin\Models\Membership;
use App\Admin\Models\Shop;
use Exception;

class PaymentMethodController extends AppBaseController
{
    /** @var  PaymentMethodRepository */
    private $paymentMethodRepository;
    private $pathView;
    private $redirectTo;
    private $paymentMethodFlag;

    public function __construct(PaymentMethodRepository $paymentMethodRepo)
    {
        $this->paymentMethodRepository = $paymentMethodRepo;
        $this->pathView = 'admin::payment_methods';
        $this->redirectTo = 'paymentMethods';
        $this->paymentMethodFlag = Constant::$paymentMethodFlag;
    }

    /**
     * Display a listing of the PaymentMethod.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(PaymentMethod::query())
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route($this->redirectTo . '.show', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href=' . route($this->redirectTo . '.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-xs btn-delete" id="delete-admin"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view($this->pathView . '.index')
            ->with([
                'routeName' => $this->redirectTo,
                'view'  => $this->pathView
            ]);
    }

    /**
     * Show the form for creating a new PaymentMethod.
     *
     * @return Response
     */
    public function create()
    {
        $shops = Shop::pluck('name', 'id')->toArray();
        return view($this->pathView . '.create')
            ->with([
                'routeName' => $this->redirectTo,
                'view'  => $this->pathView,
                'paymentMethodFlag' =>  $this->paymentMethodFlag
            ]);;
    }

    /**
     * Store a newly created PaymentMethod in storage.
     *
     * @param CreatePaymentMethodRequest $request
     *
     * @return Response
     */
    public function store(CreatePaymentMethodRequest $request)
    {
        try {
            $request->slug = str_replace(' ', '_', $request->slug);

            PaymentMethod::create([
                'name'  =>  $request->name,
                'slug'  =>  $request->slug,
                'flag'  =>  $request->flag,
                'is_active' => 1
            ]);

            Flash::success('Payment Method saved successfully.');

            return redirect(route($this->redirectTo . '.index'));
        } catch (Exception $e) {
            Flash::error('Payment Method not found');
            return back()->withInput();
        }
    }

    /**
     * Display the specified PaymentMethod.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paymentMethod = $this->paymentMethodRepository->find($id);

        if (empty($paymentMethod)) {
            Flash::error('Payment Method not found');

            return redirect(route($this->redirectTo . '.index'));
        }

        return view($this->pathView . '.show')->with([
            'paymentMethod' => $paymentMethod,
            'routeName' => $this->redirectTo,
            'view'  => $this->pathView
        ]);
    }

    /**
     * Show the form for editing the specified PaymentMethod.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paymentMethod = $this->paymentMethodRepository->find($id);


        if (empty($paymentMethod)) {
            Flash::error('Payment Method not found');

            return redirect(route($this->redirectTo . '.index'));
        }

        $shops = paymentMethod::pluck('name', 'id')->toArray();

        return view($this->pathView . '.edit')->with([
            'paymentMethod' => $paymentMethod,
            'routeName' => $this->redirectTo,
            'view'  => $this->pathView,
            'paymentMethodFlag' =>  $this->paymentMethodFlag

        ]);;
    }

    /**
     * Update the specified PaymentMethod in storage.
     *
     * @param int $id
     * @param UpdatePaymentMethodRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::find($id);

        if (empty($paymentMethod)) {
            Flash::error('Payment Method not found');

            return redirect(route($this->redirectTo . '.index'));
        }
        $slug = str_replace(' ', '_', $request->slug);

        $paymentMethod->update([
            'name'  =>  $request->name,
            'slug'  =>  $slug,
            'flag'  =>  $request->flag,
            'is_active' => 1
        ]);

        $paymentMethod = $this->paymentMethodRepository->update($request->all(), $id);

        Flash::success('Payment Method updated successfully.');

        return redirect(route($this->redirectTo . '.index'));
    }

    /**
     * Remove the specified PaymentMethod from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paymentMethod = $this->paymentMethodRepository->find($id);

        if (empty($paymentMethod)) {
            Flash::error('Payment Method not found');

            return redirect(route($this->redirectTo . '.index'));
        }

        $this->paymentMethodRepository->delete($id);

        // Flash::success('Payment Method deleted successfully.');
        // return redirect(route($this->redirectTo . '.index'));
        return response()->json(['status' => 200]);
    }
}
