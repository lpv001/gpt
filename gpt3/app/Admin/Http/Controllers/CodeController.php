<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;

use App\Admin\Http\Requests\CreateCodeRequest;
use App\Admin\Http\Requests\UpdateCodeRequest;
use App\Admin\Repositories\CodeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Schema;

use Session;
use Flash;
use Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Admin\Models\Code;

class CodeController extends AppBaseController
{
    /** @var  CodeRepository */
    private $codeRepository;
    private $months;
    private $alphabets;
    private $alphabet_x;

    public function __construct(codeRepository $codeRepo)
    {
        $this->codeRepository = $codeRepo;
        
        $this->alphabets = [1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',
                      8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',
                      15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',
                      22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z',
                      
                      27=>'a',28=>'b',29=>'c',30=>'d',31=>'e',32=>'f',33=>'g',
                      34=>'h',35=>'i',36=>'j',37=>'k',38=>'l',39=>'m',40=>'n',
                      41=>'o',42=>'p',43=>'q',44=>'r',45=>'s',46=>'t',47=>'u',
                      48=>'v',49=>'w',50=>'x',51=>'y',52=>'z'];
                      
        $this->alphabet_x = array_flip($this->alphabets);
    }

    /**
     * 
     *
     * @return Response
     */
    public function makeNumberTwoDigits($num)
    {
        $rstr = '';
        
        if ($num < 10) {
            $rstr = '0' . $num;
        }
        
        return (string)$rstr;
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function generateDigits($str, $is_str)
    {
        if (strlen($str) < 1) {
            return null;
        }
        
        if ( strstr( $str, '-' ) ) {
            $pcs = explode("-", $str);
            if ($is_str) { // it is string
                $start_str = $pcs[0];
                $end_str = $pcs[1];
                $alps = array_flip($this->alphabets);
                $start_pos = $alps[$start_str];
                $end_pos = $alps[$end_str];
                if ($start_pos > $end_pos) {
                        $response = [
                            'status'  => false,
                            'msg' => 'Invalid input of ('. $start_str . '-' . $end_str . '). It should be ('. $end_str . '-' . $start_str . ').',
                            'data' => null];
                        return $response;
                }
                
                $ars = [];
                for($i = $start_pos; $start_pos <= $end_pos; $start_pos++) {
                    $ars[] = $this->alphabets[$start_pos];
                }
                $pcs = $ars;
            } else { // it is numerics
                $start_pos = $pcs[0];
                $end_pos = $pcs[1];
                if ($start_pos > $end_pos) {
                        $response = [
                            'status'  => false,
                            'msg' => 'Invalid input of ('. $start_pos . '-' . $end_pos . '). It should be ('. $end_pos . '-' . $start_pos . ').',
                            'data' => null];
                        return $response;
                }
                
                $ars = [];
                for($i = $start_pos; $start_pos <= $end_pos; $start_pos++) {
                    $ars[] = intval($start_pos);
                }
                $pcs = $ars;
            }
        } else {
            $pcs = explode(",", $str);
        }
        
        // need to randomize array
        shuffle($pcs);
        
        $response = [
            'status'  => true,
            'msg' => '',
            'data' => $pcs];
        return $response;
    }

    /**
     *
     * @return Response
     */
    public function makeFileDigits($num)
    {
        if ($num < 10) {
            return '000' . $num;
        }
        if ($num < 100) {
            return '00' . $num;
        }
        if ($num < 1000) {
            return '0' . $num;
        }
    }

    /**
     *
     * @return Response
     */
    public function makeStrTwoDigits($alp)
    {
        $alphabets = [1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',
                      8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',
                      15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',
                      22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z',
                      
                      27=>'a',28=>'b',29=>'c',30=>'d',31=>'e',32=>'f',33=>'g',
                      34=>'h',35=>'i',36=>'j',37=>'k',38=>'l',39=>'m',40=>'n',
                      41=>'o',42=>'p',43=>'q',44=>'r',45=>'s',46=>'t',47=>'u',
                      48=>'v',49=>'w',50=>'x',51=>'y',52=>'z'];
        
        $alphabet_x = array_flip($alphabets);
        $num = $alphabet_x[$alp];
        $rstr = '';
        
        if ($num < 10) {
            $rstr = '0';
        }
        
        return (string)$rstr . $num;
    }

    /**
     *
     * @return Response
     */
    public function makeFileNames($code)
    {
        $gen_progress = unserialize($code->gen_progress);
        $x1 = $code->x1;
        
        $files = [];
        if (isset($gen_progress['cfile'])) {
            for ($i=1; $i <= $gen_progress['cfile']; $i++) {
                $files[] = $code->file_prefix . $this->makeFileDigits($code->id) 
                             . '_' . $this->makeFileDigits($i) . '.txt';
            }
        }
        
        return $files;
    }


    /**
     *
     * @return Response
     */
    public function deleteDataFiles($code)
    {
        $filenames = $this->makeFileNames($code);
        foreach($filenames as $filename) {
            $codefile = storage_path('app/' . $filename);
            if (file_exists($codefile)) {
                unlink($codefile);
            }
        }
        
        return true;
    }


    /**
     * Display a listing of the Product.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function downloadCodeData($fid)
    {
        $ff = explode('_', $fid);
        $id = $ff[0];
        $zid = $ff[1];
        
        // load code
        $code = Code::where('id', $id)->first();
        if ($code == null) {
            Flash::error('No code to download.');
            return redirect(route('codes.index'));
        }
        
        // If zip files already compressed just download
        $zipfile = storage_path('app/' . $code->file_prefix . '_' . $code->id . '_' . $zid . '.zip');
        if (file_exists($zipfile)) {
            return response()->download($zipfile);
        } else {
            Flash::error('File could not be downloaded, there were some files corrupted.');
            return redirect(route('codes.index'));
        }
    }


    /**
     * Display a listing of the Product.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function downloadIndex($id)
    {
        // load code
        $code = Code::where('id', $id)->first();
        if ($code == null) {
            Flash::error('No code to download.');
            return redirect(route('codes.index'));
        }
        
        $gen_progress = unserialize($code->gen_progress);
        $zfiles = $gen_progress['zfiles'];
        
        foreach ($zfiles as $fid => $z) {
            //$zfiles[$fid] = storage_path('app/' . $code->file_prefix . '_' . $fid . '.zip');
            $zfiles[$fid] = $code->file_prefix . '_' . $fid . '.zip';
        }
        
        return view('admin::codes.download_index', compact('code', 'zfiles'));
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
        $codes = [];
        
        // Search
        if ($request->code) {
            // Count if not yet 10 digits
            if (strlen($request->code) < 10) {
                Flash::error('Codes need to be 10 digits');
            } else {
                $arr = str_split($request->code);
                $codes = DB::table('codes')
                    ->where([
                        ['x1_field', 'LIKE', '%' . $arr[0] . '%'],
                        ['x2_field', 'LIKE', '%' . $arr[1] . '%'],
                        ['n1_field', 'LIKE', '%' . $arr[2] . '%'],
                        ['n2_field', 'LIKE', '%' . $arr[3] . '%'],
                        ['n3_field', 'LIKE', '%' . $arr[4] . '%'],
                        ['x3_field', 'LIKE', '%' . $arr[5] . '%'],
                        ['n4_field', 'LIKE', '%' . $arr[6] . '%'],
                        ['n5_field', 'LIKE', '%' . $arr[7] . '%'],
                        ['n6_field', 'LIKE', '%' . $arr[8] . '%'],
                        ['x4_field', 'LIKE', '%' . $arr[9] . '%'],
                      ])
                    ->paginate(10);
                
                $req_code = $request->code;
                return view('admin::code-data.index', compact('codes', 'req_code'));
            }
        }
        
        // Codes list
        $codes = Code::orderBy('id', 'DESC')
          ->paginate(26);
        
        return view('admin::codes.index', compact('codes'));
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        $settings = $this->months;
        return view('admin::codes.create', compact('settings'));
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function calDiff(Request $request)
    {
        try {
            $x1 = $request->x1;
            
            $gx2 = $this->generateDigits($request->x2, true);
            if ($gx2['status'] == false) {
                return back()->withInput()->with('error', $gx2['msg']);
            }
            $gn1 = $this->generateDigits($request->n1, false);
            if ($gn1['status'] == false) {
                return back()->withInput()->with('error', $gn1['msg']);
            }
            $gn2 = $this->generateDigits($request->n2, false);
            if ($gn2['status'] == false) {
                return back()->withInput()->with('error', $gn2['msg']);
            }
            $gn3 = $this->generateDigits($request->n3, false);
            if ($gn3['status'] == false) {
                return back()->withInput()->with('error', $gn3['msg']);
            }
            $gx3 = $this->generateDigits($request->x3, true);
            if ($gx3['status'] == false) {
                return back()->withInput()->with('error', $gx3['msg']);
            }
            $gn4 = $this->generateDigits($request->n4, false);
            if ($gn4['status'] == false) {
                return back()->withInput()->with('error', $gn4['msg']);
            }
            $gn5 = $this->generateDigits($request->n5, false);
            if ($gn5['status'] == false) {
                return back()->withInput()->with('error', $gn5['msg']);
            }
            $gn6 = $this->generateDigits($request->n6, false);
            if ($gn6['status'] == false) {
                return back()->withInput()->with('error', $gn6['msg']);
            }
            $gx4 = $this->generateDigits($request->x4, true);
            if ($gx4['status'] == false) {
                return back()->withInput()->with('error', $gx4['msg']);
            }
            
            $x2 = $gx2['data'];
            $n1 = $gn1['data'];
            $n2 = $gn2['data'];
            $n3 = $gn3['data'];
            $x3 = $gx3['data'];
            $n4 = $gn4['data'];
            $n5 = $gn5['data'];
            $n6 = $gn6['data'];
            $x4 = $gx4['data'];
            
            $total_diff = strlen($x1) * count($x2) * count($n1) * count($n2) * count($n3) * count($x3) * count($n4) * count($n5) * count($n6) * count($x4);
            
            return response()->json([
              'status' => true,
              'data'  => $total_diff
            ]);
            
        } catch (Exception $e) {
            return response()->json([
              'status' => false,
              'data'  => 'NG'
            ]);
        }
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
            $x1 = $request->x1;
            
            $gx2 = $this->generateDigits($request->x2, true);
            if ($gx2['status'] == false) {
                return back()->withInput()->with('error', $gx2['msg']);
            }
            $gn1 = $this->generateDigits($request->n1, false);
            if ($gn1['status'] == false) {
                return back()->withInput()->with('error', $gn1['msg']);
            }
            $gn2 = $this->generateDigits($request->n2, false);
            if ($gn2['status'] == false) {
                return back()->withInput()->with('error', $gn2['msg']);
            }
            $gn3 = $this->generateDigits($request->n3, false);
            if ($gn3['status'] == false) {
                return back()->withInput()->with('error', $gn3['msg']);
            }
            $gx3 = $this->generateDigits($request->x3, true);
            if ($gx3['status'] == false) {
                return back()->withInput()->with('error', $gx3['msg']);
            }
            $gn4 = $this->generateDigits($request->n4, false);
            if ($gn4['status'] == false) {
                return back()->withInput()->with('error', $gn4['msg']);
            }
            $gn5 = $this->generateDigits($request->n5, false);
            if ($gn5['status'] == false) {
                return back()->withInput()->with('error', $gn5['msg']);
            }
            $gn6 = $this->generateDigits($request->n6, false);
            if ($gn6['status'] == false) {
                return back()->withInput()->with('error', $gn6['msg']);
            }
            $gx4 = $this->generateDigits($request->x4, true);
            if ($gx4['status'] == false) {
                return back()->withInput()->with('error', $gx4['msg']);
            }
            
            $x2 = $gx2['data'];
            $n1 = $gn1['data'];
            $n2 = $gn2['data'];
            $n3 = $gn3['data'];
            $x3 = $gx3['data'];
            $n4 = $gn4['data'];
            $n5 = $gn5['data'];
            $n6 = $gn6['data'];
            $x4 = $gx4['data'];
            
            $total_diff = strlen($x1) * count($x2) * count($n1) * count($n2) * count($n3) * count($x3) * count($n4) * count($n5) * count($n6) * count($x4);
            $format_data = [
                'head' => $x1,
                'head_id' => intval($this->alphabet_x[$x1]),
                'x1' => $x1,
                'x2' => $x2,
                'n1' => $n1,
                'n2' => $n2,
                'n3' => $n3,
                'x3' => $x3,
                'n4' => $n4,
                'n5' => $n5,
                'n6' => $n6,
                'x4' => $x4,
                'ndiff' => $total_diff
            ];
            
            $input = [
                'format_data'  =>  serialize($format_data),
                'x1_search' => $x1,
                'x2_search' => implode(',', $x2),
                'x1' => $request->x1,
                'x2' => $request->x2,
                'n1' => $request->n1,
                'n2' => $request->n2,
                'n3' => $request->n3,
                'x3' => $request->x3,
                'n4' => $request->n4,
                'n5' => $request->n5,
                'n6' => $request->n6,
                'x4' => $request->x4,
                'x1_field' => $x1,
                'x2_field' => implode(',', $x2),
                'n1_field' => implode(',', $n1),
                'n2_field' => implode(',', $n2),
                'n3_field' => implode(',', $n3),
                'x3_field' => implode(',', $x3),
                'n4_field' => implode(',', $n4),
                'n5_field' => implode(',', $n5),
                'n6_field' => implode(',', $n6),
                'x4_field' => implode(',', $x4),
                'file_prefix' => $request->file_prefix,
                'ndiff' => $total_diff,
                'head_id' => intval($this->alphabet_x[$x1]),
                'head'  =>  $format_data['x1'],
                'is_ready' => $request->is_ready ? 1 : 0,
                'is_used' => 0
            ];
            $code = $this->codeRepository->create($input);

            Flash::success('Code saved successfully.');

            return redirect(route('codes.index'));
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Critical error! Please contact our system support.');
        }
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function updateCodesHelper($id, $request)
    {
        try {
            $x1 = $request->x1;
            
            $gx2 = $this->generateDigits($request->x2, true);
            if ($gx2['status'] == false) {
                return back()->withInput()->with('error', $gx2['msg']);
            }
            $gn1 = $this->generateDigits($request->n1, false);
            if ($gn1['status'] == false) {
                return back()->withInput()->with('error', $gn1['msg']);
            }
            $gn2 = $this->generateDigits($request->n2, false);
            if ($gn2['status'] == false) {
                return back()->withInput()->with('error', $gn2['msg']);
            }
            $gn3 = $this->generateDigits($request->n3, false);
            if ($gn3['status'] == false) {
                return back()->withInput()->with('error', $gn3['msg']);
            }
            $gx3 = $this->generateDigits($request->x3, true);
            if ($gx3['status'] == false) {
                return back()->withInput()->with('error', $gx3['msg']);
            }
            $gn4 = $this->generateDigits($request->n4, false);
            if ($gn4['status'] == false) {
                return back()->withInput()->with('error', $gn4['msg']);
            }
            $gn5 = $this->generateDigits($request->n5, false);
            if ($gn5['status'] == false) {
                return back()->withInput()->with('error', $gn5['msg']);
            }
            $gn6 = $this->generateDigits($request->n6, false);
            if ($gn6['status'] == false) {
                return back()->withInput()->with('error', $gn6['msg']);
            }
            $gx4 = $this->generateDigits($request->x4, true);
            if ($gx4['status'] == false) {
                return back()->withInput()->with('error', $gx4['msg']);
            }
            
            $x2 = $gx2['data'];
            $n1 = $gn1['data'];
            $n2 = $gn2['data'];
            $n3 = $gn3['data'];
            $x3 = $gx3['data'];
            $n4 = $gn4['data'];
            $n5 = $gn5['data'];
            $n6 = $gn6['data'];
            $x4 = $gx4['data'];
            
            $total_diff = strlen($x1) * count($x2) * count($n1) * count($n2) * count($n3) * count($x3) * count($n4) * count($n5) * count($n6) * count($x4);
            $format_data = [
                'head' => $x1,
                'head_id' => intval($this->alphabet_x[$x1]),
                'x1' => $x1,
                'x2' => $x2,
                'n1' => $n1,
                'n2' => $n2,
                'n3' => $n3,
                'x3' => $x3,
                'n4' => $n4,
                'n5' => $n5,
                'n6' => $n6,
                'x4' => $x4,
                'ndiff' => $total_diff
            ];
            
            DB::table('codes')->where('id',$id)
            ->update([
                'format_data' => serialize($format_data), 
                'x1_search' => $x1,
                'x2_search' => implode(',', $x2),
                'x1'        => $request->x1,
                'x2'        => $request->x2,
                'n1'        => $request->n1,
                'n2'        => $request->n2,
                'n3'        => $request->n3,
                'x3'        => $request->x3,
                'n4'        => $request->n4,
                'n5'        => $request->n5,
                'n6'        => $request->n6,
                'x4'        => $request->x4,
                'x1_field'  => $x1,
                'x2_field'  => implode(',', $x2),
                'n1_field'  => implode(',', $n1),
                'n2_field'  => implode(',', $n2),
                'n3_field'  => implode(',', $n3),
                'x3_field'  => implode(',', $x3),
                'n4_field'  => implode(',', $n4),
                'n5_field'  => implode(',', $n5),
                'n6_field'  => implode(',', $n6),
                'x4_field'  => implode(',', $x4),
                'file_prefix' => $request->file_prefix,
                'ndiff' => $total_diff,
                'head_id' => intval($this->alphabet_x[$x1]),
                'head'  =>  $format_data['x1'],
                'is_ready' => $request->is_ready ? 1 : 0,
                'is_used' => 0
              ]);
              
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function updateCodes(Request $request)
    {
            Flash::success('Code successfully.');
            return redirect(route('codes.index'));
      
      if ($request->updatecode == 2) {
        $rows = DB::table('codes')->get();
        foreach ($rows as $c) {
          $this->updateCodesHelper($c);
        }
      }
      
      // Clear
      if ($request->updatecode == 3) {
        $rows = DB::table('codes')->get();
        foreach ($rows as $c) {
            DB::table('codes')->where('id',$c->id)->update(['gen_progress' => null, 'cdiff'  => 0, 'is_ready' => 1]);
        }
      }
      
      // Clear zip
      if ($request->updatecode == 4) {
        $rows = DB::table('codes')->get();
        foreach ($rows as $c) {
            $gen_progress = unserialize($c->gen_progress);
            unset($gen_progress['zfiles']);
            DB::table('codes')->where('id',$c->id)->update(['is_ready' => 3, 'gen_progress' => serialize($gen_progress)]);
        }
      }
      
      dd("helllo", $rows);
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
        $edit = 1;
        
        if (empty($code)) {
            Flash::error('Code not found');
            return redirect(route('codes.index'));
        }
        
        if ($code->is_ready > 0) {
            Flash::error('Code is running.');
            return redirect(route('codes.index'));
        }
        
        return view('admin::codes.edit', compact(
            'edit',
            'code'
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
            $xcode = $this->codeRepository->find($id);
            
            if (empty($xcode)) {
                Flash::error('Code not found');
                return redirect(route('codes.index'));
            }
            
            $ret = $this->updateCodesHelper($id, $request);
            if ($ret) {
                Flash::success('code updated successfully.');
                return redirect(route('codes.index'));
            } else {
                Flash::error('Critical Error UP001');
                return redirect(route('codes.index'));
            }
        } catch (\Exception $e) {
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
            
            // delete code data files
            $this->deleteDataFiles($code);
            
            // delete zip file
            $zipfile = storage_path('app/code_data_' . $code->head_id . '.zip');
            if (file_exists($zipfile)) {
                unlink($zipfile);
            }
            
            // drop tables
            $format_data = unserialize($code->format_data);
            foreach ($format_data['x2'] as $x2) {
                $codenumber = $this->makeStrTwoDigits($code->x1) . $this->makeStrTwoDigits($x2);
                $table_name = 'code_data'.$codenumber;
                Schema::dropIfExists($table_name);
                //DB::table($table_name)->truncate();
            }
            
            Flash::success('code deleted successfully.');
            return redirect(route('codes.index'));
        }
        Flash::error('Code not found');
        return redirect(route('codes.index'));
    }
}
