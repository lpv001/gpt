<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateDistrictRequest;
use App\Admin\Http\Requests\UpdateDistrictRequest;
use App\Admin\Repositories\DistrictRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\City;
use App\Admin\Models\District;
use DataTables;

class DistrictController extends AppBaseController
{
    /** @var  DistrictRepository */
    private $districtRepository;

    public function __construct(DistrictRepository $districtRepo)
    {
        $this->districtRepository = $districtRepo;
    }

    /**
     * Display a listing of the District.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $districts = $this->districtRepository->all();

        if ($request->ajax()) {
            return Datatables::of($districts)
                ->addColumn('city', function($data){
                    return City::where('id', $data->city_province_id)->pluck('default_name')->toArray();
                })
                ->addColumn('action', function($data){
                    return '<div class="btn-group">
                                <a href='.route("districts.show", [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href='.route('districts.edit', [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="'.$data->id.'" class="btn btn-danger btn-xs" id="deleteDistrict"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin::districts.index')
            ->with('districts', $districts);
    }

    /**
     * Show the form for creating a new District.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::districts.create');
    }

    /**
     * Store a newly created District in storage.
     *
     * @param CreateDistrictRequest $request
     *
     * @return Response
     */
    public function store(CreateDistrictRequest $request)
    {
        $input = $request->all();

        $district = $this->districtRepository->create($input);

        Flash::success('District saved successfully.');

        return redirect(route('districts.index'));
    }

    /**
     * Display the specified District.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        return view('admin::districts.show')->with('district', $district);
    }

    /**
     * Show the form for editing the specified District.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        return view('admin::districts.edit')->with('district', $district);
    }

    /**
     * Update the specified District in storage.
     *
     * @param int $id
     * @param UpdateDistrictRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDistrictRequest $request)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        $district = $this->districtRepository->update($request->all(), $id);

        Flash::success('District updated successfully.');

        return redirect(route('districts.index'));
    }

    /**
     * Remove the specified District from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        $this->districtRepository->delete($id);

        Flash::success('District deleted successfully.');

        return redirect(route('districts.index'));
    }

    public function getDistrict($id)
    {
        $districts = District::where('city_province_id', $id)->pluck('default_name', 'id');

        if (count($districts) > 0)
            return response()->json(['status' => 200, 'data' => $districts]);

        return response()->json(['status' => 405, 'data' => ['no'] ]);
    }
}
