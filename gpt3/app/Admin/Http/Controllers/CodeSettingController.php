<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateCodeRequest;
use App\Admin\Http\Requests\UpdateCodeRequest;
use App\Admin\Repositories\CodeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Admin\Models\Code;

class CodeSettingController extends AppBaseController
{
    /** @var  CodeRepository */
    private $codeRepository;
    private $months;

    public function __construct(codeRepository $codeRepo)
    {
        $this->codeRepository = $codeRepo;
        
        $this->months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', 
                         '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August',
                         '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];
    }

    /**
     * Display a listing of the Product.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = DB::table('code_settings')
                    ->paginate(10);
        return view('admin::code-settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        $settings = $this->months;
        return view('admin::code-settings.create', compact('settings'));
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateCodeRequest $request)
    {
        try {
            // This should load from settings
            $settings = [
                '1' => 'G', '2' => 'H', '3' => 'I', '4' => 'J',
                '5' => 'K', '6' => 'L', '7' => 'M', '8' => 'N',
                '9' => 'O', '10' => 'P', '11' => 'Q', '12' => 'R'];
            
            $format_data = [
                'x1' => $settings[$request->setting_id],
                'month' => $request->setting_id,
                'month_setting' => $settings[$request->setting_id]
            ];
            
            $input = [
                'setting_id'   => 1,
                'format_data'  =>  serialize($format_data),
                'head'  =>  $format_data['x1'],
                'is_ready' => 0,
                'is_used' => 0
            ];
            
            $code = $this->codeRepository->create($input);

            Flash::success('Code saved successfully.');

            return redirect(route('codes.index'));
        } catch (Exception $e) {
            return \Redirect::back()->withInput($request->all());
        }
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $code = Code::find($id);
        
        return view('admin::codes.show', compact('code'));
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $code = $this->codeRepository->find($id);
        $settings = $this->months;
        $edit = 1;
        
        if (empty($code)) {
            Flash::error('Code not found');
            return redirect(route('codes.index'));
        }

        return view('admin::codes.edit', compact(
            'edit',
            'code',
            'settings'
        ));
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $code = $this->codeRepository->find($id);
            
            if (empty($code)) {
                Flash::error('Code not found');
                return redirect(route('codes.index'));
            }
            
            $input = [
                'is_ready'   => $request->be_generated? 1 : 0
            ];
            
            $code = $this->codeRepository->update($input, $id);
            
            Flash::success('code updated successfully.');
            return redirect(route('codes.index'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return \Redirect::back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $code = $this->codeRepository->find($id);
        
        if ($code) {
            $this->codeRepository->delete($id);
            return response()->json(['status'   =>  200,    'message'   =>  'Product Deleted Successfully!']);
        }
        return response()->json(['status'   =>  400,    'message'   =>  'Product not found!']);
    }
}
