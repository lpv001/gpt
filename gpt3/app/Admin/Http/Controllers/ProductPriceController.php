<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateProductPriceRequest;
use App\Admin\Http\Requests\UpdateProductPriceRequest;
use App\Admin\Repositories\ProductPriceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Admin\Models\Product;
use App\Admin\Models\ProductPrice;
use App\Admin\Models\City;
use App\Admin\Models\Unit;
use App\Admin\Models\Membership;
use Flash;
use DB;
use DataTables;
use Exception;
use Response;

class ProductPriceController extends AppBaseController
{
    /** @var  ProductPriceRepository */
    private $productPriceRepository;

    public function __construct(ProductPriceRepository $productPriceRepo)
    {
        $this->productPriceRepository = $productPriceRepo;
    }

    /**
     * Display a listing of the ProductPrice.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // $products = Product
        $productPrices = ProductPrice::with([
            'unit' => function($q) {
                return $q->select('id', 'name');
            }, 
            'product' => function($q) {
                return $q->select('id', 'name');
            }, 
            'city' => function($q) {
                return $q->select('id', 'default_name');
            }
            ])->orderBy('product_id', 'ASC')->get();
        return view('admin::product_prices.index', compact('productPrices'));
    }

    /**
     * Show the form for creating a new ProductPrice.
     *
     * @return Response
     */
    public function create()
    {
        $product = Product::pluck('name', 'id')->toArray();
        $city = City::pluck('default_name', 'id')->toArray();
        $unit = Unit::pluck('name', 'id')->toArray();
        $type = Membership::pluck('name', 'id')->toArray();
        return view('admin::product_prices.create', compact('product','city', 'unit','type'));
    }

    /**
     * Store a newly created ProductPrice in storage.
     *
     * @param CreateProductPriceRequest $request
     *
     * @return Response
     */
    public function store(CreateProductPriceRequest $request)
    {
        if ( isset($request->product_id) )
        {
            return dd($request->all());
            foreach ($request->product_id as $key => $value) {
                
                ProductPrice::create( [
                    'product_id'    =>      $value,
                    'type_id'       =>      0,
                    'unit_id'       =>      $request->unit_id[$key],
                    'city_id'       =>      $request->city_id[$key] ?? 0,
                    'qty_per_unit'  =>      $request->qty_per_unit[$key],
                    'unit_price'    =>      0,
                    'sale_price'    =>      0,
                    'distributor'   =>      $request->distributor[$key],
                    'wholesaler'    =>      $request->wholesaler[$key],
                    'retailer'      =>      $request->retailer[$key],
                    'buyer'         =>      $request->buyer[$key],
                    'flag'          =>      0,
                ] );
            }
        }

        Flash::success('Product Price saved successfully.');

        return redirect(route('productPrices.index'));
    }

    /**
     * Display the specified ProductPrice.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $productPrice = $this->productPriceRepository->find($id);

        if (empty($productPrice)) {
            Flash::error('Product Price not found');

            return redirect(route('productPrices.index'));
        }
        return view('admin::product_prices.show')->with('productPrice', $productPrice);
    }

    /**
     * Show the form for editing the specified ProductPrice.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $productPrice = $this->productPriceRepository->find($id);
        
        if (empty($productPrice)) {
            Flash::error('Product Price not found');

            return redirect(route('productPrices.index'));
        }

        $product = Product::pluck('name', 'id')->toArray();
        $city = City::pluck('default_name', 'id')->toArray();
        //$unit = Unit::where('id', $productPrice->unit_id)->pluck('name', 'id')->toArray();
        $unit = Unit::pluck('name','id')->toArray();
        $type = Membership::pluck('name', 'id')->toArray();
        $edit = 0;

        return view('admin::product_prices.edit', compact('productPrice','product','city', 'unit','type','edit'));
    }

    /**
     * Update the specified ProductPrice in storage.
     *
     * @param int $id
     * @param UpdateProductPriceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductPriceRequest $request)
    {
        try {
            $productPrice = $this->productPriceRepository->find($id);

            if (!$productPrice) {
                Flash::success( 'Product not found!');
                return redirect(route('productPrices.index'));
            }
            ProductPrice::where('product_id', $productPrice)->update(['flag' => 0]);
            $productPrice->update( [
                // 'product_id'    =>      $value,
                // 'type_id'       =>      0,
                // 'unit_id'       =>      $request->unit_id[$key],
                // 'city_id'       =>      $request->city_id[$key] ?? 0,
                // 'qty_per_unit'  =>      $request->qty_per_unit[$key],
                'unit_price'    =>      0,
                'sale_price'    =>      0,
                'distributor'   =>      $request->distributor[0],
                'wholesaler'    =>      $request->wholesaler[0],
                'retailer'      =>      $request->retailer[0],
                'buyer'         =>      $request->buyer[0],
                // 'flag'          =>      1,
                'flag'          =>      $request->flag ? 1 : 0,
            ] );

            Flash::success('Product Price updated successfully.');
            return redirect(route('productPrices.index'));

        } catch (Exception $e) {
            Flash::success($e->getMessage());
            return redirect(route('productPrices.index'));
        }
        
    }

    /**
     * Remove the specified ProductPrice from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $productPrice = $this->productPriceRepository->find($id);

        if (empty($productPrice)) {
            Flash::error('Product Price not found');

            return redirect(route('productPrices.index'));
        }

        $this->productPriceRepository->delete($id);

        Flash::success('Product Price deleted successfully.');

        return redirect(route('productPrices.index'));
    }
    
    /**
     * Search the  ProductPrice from storage.
     *
     *
     * @throws \Exception
     *
     * @return Response
     */

    public function getUnit($id)
    {
        $data = ProductPrice::where('product_id', $id)->with('unit')->get();
        $units = [];
        foreach ($data as $key => $value) {
            $units[$value->unit_id] = $value->unit->name;
        }

        return response()->json($units);
    }
}
