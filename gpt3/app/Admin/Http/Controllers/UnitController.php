<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateUnitRequest;
use App\Admin\Http\Requests\UpdateUnitRequest;
use App\Admin\Repositories\UnitRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Admin\Models\Unit;
use App\Admin\Models\UnitTranslation;
use Flash;
use Response;
use DataTables;


class UnitController extends AppBaseController
{
    /** @var  UnitRepository */
    private $unitRepository;

    public function __construct(UnitRepository $unitRepo)
    {
        $this->unitRepository = $unitRepo;
    }

    /**
     * Display a listing of the Unit.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $units = $this->unitRepository->all();

        return view('admin::units.index')
            ->with('units', $units);
    }

    /**
     * Show the form for creating a new Unit.
     *
     * @return Response
     */
    public function create()
    {
        $units = Unit::pluck('name', 'id');
        return view('admin::units.create', compact('units'));
    }

    /**
     * Store a newly created Unit in storage.
     *
     * @param CreateUnitRequest $request
     *
     * @return Response
     */
    public function store(CreateUnitRequest $request)
    {
        $input = [
            'parent_id' => $request->parent_id ?? 0,
            'name' => $request->name[0],
            // 'description' => $request->description ?? '',
        ];

        $unit = $this->unitRepository->create($input);

        $_name[0] = ['local' => 'en', 'name' => ''];
        $_name[1] = ['local' => 'km', 'name' => ''];
        $_name[2] = ['local' => 'cn', 'name' => ''];
        foreach ($request->lang as $item_key => $item) {
            if ($item === 'en') $_name[0]['name'] = $request->name[$item_key];
            if ($item === 'km') $_name[1]['name'] = $request->name[$item_key];
            if ($item === 'cn') $_name[2]['name'] = $request->name[$item_key];
        }

        $lang = ['en', 'km', 'cn'];
        foreach ($lang as $key => $value) {
            UnitTranslation::create([
                'unit_id'   => $unit->id,
                'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
                'locale'     =>  $value,
            ]);
        }

        Flash::success('Unit saved successfully.');

        return redirect(route('units.index'));
    }

    /**
     * Display the specified Unit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $unit = $this->unitRepository->find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('units.index'));
        }

        return view('admin::units.show')->with('unit', $unit);
    }

    /**
     * Show the form for editing the specified Unit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $unit = $this->unitRepository->find($id);
        $units = Unit::pluck('name', 'id');
        $unit_translateion = UnitTranslation::where('unit_id', $id)->pluck('name', 'locale');

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('units.index'));
        }

        return view('admin::units.edit', compact('unit', 'units', 'unit_translateion'));
    }

    /**
     * Update the specified Unit in storage.
     *
     * @param int $id
     * @param UpdateUnitRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUnitRequest $request)
    {
        $unit = $this->unitRepository->find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('units.index'));
        }

        $input = [
            'parent_id' => $request->parent_id ?? null,
            'name' => $request->name[0],
            // 'description' => $request->description ?? '',
        ];

        $unit = $this->unitRepository->update($input, $id);

        $_name[0] = ['local' => 'en', 'name' => ''];
        $_name[1] = ['local' => 'km', 'name' => ''];
        $_name[2] = ['local' => 'cn', 'name' => ''];
        foreach ($request->lang as $item_key => $item) {
            if ($item === 'en') $_name[0]['name'] = $request->name[$item_key];
            if ($item === 'km') $_name[1]['name'] = $request->name[$item_key];
            if ($item === 'cn') $_name[2]['name'] = $request->name[$item_key];
        }

        $lang = ['en', 'km', 'cn'];
        foreach ($lang as $key => $value) {
            UnitTranslation::updateOrCreate([
                'unit_id'  =>  $id,
                'locale'    =>  $value
            ], [
                'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
            ]);
            // UnitTranslation::where('unit_id', $id)->where('locale', $value)->update([
            //     'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
            // ]);
        }

        Flash::success('Unit updated successfully.');

        return redirect(route('units.index'));
    }

    /**
     * Remove the specified Unit from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $unit = $this->unitRepository->find($id);

        if (empty($unit)) {
            Flash::error('Unit not found');

            return redirect(route('units.index'));
        }

        $this->unitRepository->delete($id);
        UnitTranslation::where('unit_id', $id)->delete();

        Flash::success('Unit deleted successfully.');

        return redirect(route('units.index'));
    }
}
