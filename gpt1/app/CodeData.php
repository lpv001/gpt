<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use App;
use DB;

class CodeData extends Model
{
    protected $table = 'codes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'setting_id',
        'format_data',
        'head',
        'is_ready',
        'is_used'
    ];

    public $timestamps = true;


    /**
    *
    */
    public function truncateData()
    {
        for ($i = 1; $i < 27; $i++) {
            DB::statement( 'TRUNCATE code_data'.$i );
        }
    } //EOF


    /**
    *
    */
    public function genx3s($alphabets, $x1, $ndif)
    {
        $na = count($alphabets);

        $alps = array_flip($alphabets);
        $x3p = $alps[$x1] + 1;

        $ra = [];
        $c = 1;
        for ($p = $x3p; $p < ($x3p + 10); $p++) {
            if ($p > ($na - 1)) {
                $p = 0;
            }
            
            $ra[] = $alphabets[$p];
            
            if ($c == $ndif) {
                break;
            }
            $c++;
        }

        return $ra;
    }

    /**
    *
    */
    public function get10chars($ar, $x1, $ndif)
    {
        $alps = array_flip($ar);
        unset($alps[$x1]);
        $x2s = array_flip($alps);
        //shuffle($x2s);
        $rar = [];
        for ($i = 0; $i < $ndif; $i++)
        {
            $rar[] = $x2s[$i];
        }

        return $rar;
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
     */
    public function insertDB($format_data, $data, $code_id)
    {
        $x1 = $format_data['x1'];
        $x2s = $format_data['x2'];
        
        foreach ($data as $ix2 => $d) {
            $x2 = $format_data['x2'][$ix2];
            $codenumber = $this->makeStrTwoDigits($x1) . $this->makeStrTwoDigits($x2);
            $table_name = 'code_datai'.$code_id.'c'.$codenumber;
            
            // Create table if not existed
            if (!Schema::hasTable($table_name)) {
                // First insert into code list if not exists
                $code_list = DB::table('code_list')->where('head', $x1.$x2)->first();
                if (!$code_list) {
                    DB::table('code_list')->insert([['head' => $x1.$x2, 'table_code' => $table_name]]);
                }
                
                Schema::create($table_name, function (Blueprint $table) {
                    $table->tinyInteger('code_id');
                    $table->string('data', 11);
                    $table->boolean('is_used', 1)->default('0');
                });
            }
            
            // Insert data
            if (count($d) > 2000) {
                $chunk_data = array_chunk($d, 2000);
                if (isset($chunk_data) && !empty($chunk_data)) {
                    foreach ($chunk_data as $chunk_data_val) {
                        DB::table($table_name)->insert($chunk_data_val);
                    }
                }
            } else {
                DB::table($table_name)->insert($d);
            }
        } // EOF foreach()
    } // EOF

   /**
    *
    */
    public function generateCodes($format_data)
    {
        $db = [];
        $data = [];
        $str_data = '';
        $c = $format_data['cdiff'];
        $i = 0;
        
        $nc = count($format_data['n4']) * count($format_data['n5']) * count($format_data['n6']) * count($format_data['x4']);
        if ($nc < 20000) {
            $nc = 20000;
        }
        
        $gen_progress = $format_data['gen_progress'];
        
        if (!is_array($format_data['x1'])) {
            $format_data['x1'] = [$format_data['x1']];
        }
        $mx1 = count($format_data['x1']);
        $mx2 = count($format_data['x2']);
        $mn1 = count($format_data['n1']);
        $mn2 = count($format_data['n2']);
        $mn3 = count($format_data['n3']);
        $mx3 = count($format_data['x3']);
        $mn4 = count($format_data['n4']);
        $mn5 = count($format_data['n5']);
        $mn6 = count($format_data['n6']);
        $mx4 = count($format_data['x4']);
        
        $limit_file_row = 1000000;
        
        for ($ix1 = $gen_progress['ix1']; $ix1 < $mx1; $ix1++) {
            if ($i > 0) {
                $gen_progress['ix2'] = 0;
            }
            $gen_progress['ix1'] = $ix1;
            $x1 = $format_data['x1'][$ix1];
            
            for ($ix2 = $gen_progress['ix2']; $ix2 < $mx2; $ix2++) {
                if ($i > 0) {
                    $gen_progress['in1'] = 0;
                }
                $gen_progress['ix2'] = $ix2;
                $x2 = $format_data['x2'][$ix2];
                
                for ($in1 = $gen_progress['in1']; $in1 < $mn1; $in1++) {
                    if ($i > 0) {
                        $gen_progress['in2'] = 0;
                    }
                    $gen_progress['in1'] = $in1;
                    $n1 = $format_data['n1'][$in1];
                    
                    for ($in2 = $gen_progress['in2']; $in2 < $mn2; $in2++) {
                        if ($i > 0) {
                            $gen_progress['in3'] = 0;
                        }
                        $gen_progress['in2'] = $in2;
                        $n2 = $format_data['n2'][$in2];
                        
                        for ($in3 = $gen_progress['in3']; $in3 < $mn3; $in3++) {
                            if ($i > 0) {
                              $gen_progress['ix3'] = 0;
                            }
                            $gen_progress['in3'] = $in3;
                            $n3 = $format_data['n3'][$in3];
                            
                            for ($ix3 = $gen_progress['ix3']; $ix3 < $mx3; $ix3++) {
                                $gen_progress['ix3'] = $ix3;
                                $x3 = $format_data['x3'][$ix3];
                                
                                if ($i > $nc || ($gen_progress['crow'] > $limit_file_row)) {
                                    shuffle($data);
                                    $str_data = implode(PHP_EOL, $data) . PHP_EOL;
                                    return ['db' => $db, 'str' => $str_data, 'cdiff' => $c, 'gen_progress' => $gen_progress];
                                }
                                foreach ($format_data['n4'] as $n4) {
                                    foreach ($format_data['n5'] as $n5) {
                                        foreach ($format_data['n6'] as $n6) {
                                            foreach ($format_data['x4'] as $x4) {
                                                $str_format = $x1. $x2. $n1 . $n2. $n3 . $x3 . $n4 . $n5 . $n6 . $x4;
                                                $db[$ix2][] = ['code_id' => $format_data['code_id'], 'data' => $str_format];
                                                //$str_data .= $str_format . PHP_EOL;
                                                $data[] = $str_format;
                                                $c++;
                                                $i++;
                                                $gen_progress['crow']++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } // EOF for ($in2...)
                } // EOF for ($in1...)
            } // EOF for ($ix2...)
        } // EOF for ($ix1...)
        
        if (count($data) > 0) {
            shuffle($data);
            $str_data = implode(PHP_EOL, $data) . PHP_EOL;
            return ['db' => $db, 'str' => $str_data, 'cdiff' => $c, 'gen_progress' => $gen_progress];
        } else {
            return null;
        }
    } // EOF


    /**
     * 
     * @return Response
     */
    public function makeFileName($code, $fid)
    {
        return $code->file_prefix . $this->makeFileDigits($code->id) . '_' . $this->makeFileDigits($fid) . '.txt';
    }


    /**
     * This is used by admin and will be removed.
     * @return Response
     */
    public function makeFileNames($code, $gen_progress)
    {
        $files = [];
        if (isset($gen_progress['cfile'])) {
            for ($i=1; $i <= $gen_progress['cfile']; $i++) {
                if ($i == 5) {
                    $files[$i] = ['fid' => $i,'total_row' => 16];
                } else {
                    $files[$i] = ['fid' => $i,'total_row' => 20];
                }
            }
        }
        
        return $files;
    }


    /**
     * 
     * @return Response
     */
    public function devClearCode($id)
    {
        $code = DB::table('codes')->where('id', $id)->first();
        
        if ($code) {
            $gen_progress = unserialize($code->gen_progress);
            $gen_progress['nfile'] = 1;
            $gen_progress['nrow'] = 0;
            
            
            /*
            $gen_progress['nfile'] = 1;
            $gen_progress['nrow'] = 0;
            $gen_progress['cfile'] = 5;
            $files = $this->makeFileNames($code, $gen_progress);
            
            // update progress
            DB::table('codes')->where('id',$code->id)->update(['is_ready' => 2, 'files' => serialize($files), 'gen_progress' => serialize($gen_progress)]);
            */
            
            DB::table('codes')->where('id',$code->id)->update(['is_ready' => 2, 'files' => '', 'gen_progress' => serialize($gen_progress)]);
        }
        
        dd("cleared");
    }


    /**
     * 
     * @return Response
     */
    public function devGenerateFiles()
    {
        $code = DB::table('codes')->where('is_ready', 2)->first();
        $gen_progress = unserialize($code->gen_progress);
        
        $gen_progress['cfile'] = 5;
        $files = $this->makeFileNames($code, $gen_progress);
        
        // update progress
        DB::table('codes')->where('id',$code->id)->update(['files' => serialize($files), 'gen_progress' => serialize($gen_progress)]);
        
        return true;
    }


    /**
     * 
     * @return Response
     */
    public function genFiles($limit_file_row)
    {
        $code = DB::table('codes')->where('is_ready', 2)->first();
        
        $files = [];
        $gen_progress = unserialize($code->gen_progress);
        
        for ($i=1; $i <= $gen_progress['cfile']; $i++) {
            $files[$i] = ['fid' => $i,'total_row' => $limit_file_row];
        }
        
        $remainder = $code->ndiff % $limit_file_row;
        if ($remainder > 0) {
            $i = $gen_progress['cfile'];
            $files[$i] = ['fid' => $i,'total_row' => $remainder];
        }
        
        DB::table('codes')->where('id',$code->id)->update(['files' => serialize($files)]);
        
        return $files;
    }


    /**
     *
     */
    public function deleteFiles($code, $files)
    {
        foreach ($files as $fi => $fh) {
            $codefile = storage_path('app/' . 't_'. $this->makeFileName($code, $fh['fid']));
            if (file_exists($codefile)) {
                unlink($codefile);
            }
        }
    }

    /**
     *
     */
    public function runCodeRandom($code, $limit_file_row, $gen_progress, $files, $i, $is_debug)
    {
        if (!$files) {
            $files = $this->genFiles($limit_file_row);
        }
        
        // Working on read size
        $row_size = 12; //fix
        $read_rows = intdiv(intdiv($limit_file_row, $gen_progress['cfile']), 2);
        $bar = $ar = $ar1 = $ar2 = [];
        
        foreach ($files as $fi => $fh) {
            // Working on last file which num rows less than limit file row
            if ($fh['total_row'] < $limit_file_row) {
                $req_rows = intdiv($fh['total_row'], $gen_progress['cfile']);
            } else {
                $req_rows = $read_rows;
            }
            
            // Working on reading times
            if (!isset($files[$fi]['start_splits'])) {
                $remainder = $fh['total_row'] % $req_rows;
                $files[$fi]['remain_rows'] = $remainder;
                
                $quotient = ($fh['total_row'] - $remainder) / $req_rows;
                $remain_splits = $quotient % 2;
                $nsplits = ($quotient - $remain_splits) / 2;
                $files[$fi]['start_splits'] = $nsplits;
                $files[$fi]['start_csplit'] = 0;
                $files[$fi]['end_splits'] = $nsplits + $remain_splits;
                $files[$fi]['end_csplit'] = $files[$fi]['start_splits'] + $files[$fi]['end_splits'];
            }
            
            // First read
            if ($files[$fi]['start_csplit'] < $files[$fi]['start_splits']) {
                $read_pos = $files[$fi]['start_csplit'] * $req_rows * $row_size;
                $buf = file_get_contents(Storage::disk('local')->path('t_'. $this->makeFileName($code, $fh['fid'])), FALSE, NULL, $read_pos, $req_rows * $row_size);
                $files[$fi]['start_csplit'] = $files[$fi]['start_csplit'] + 1;
                $ar = explode(PHP_EOL, $buf);
                $p = array_pop($ar);
            }
            
            // End read
            if ($files[$fi]['end_csplit'] > $files[$fi]['start_splits']) {
                $files[$fi]['end_csplit'] = $files[$fi]['end_csplit'] - 1;
                $read_pos = $files[$fi]['end_csplit'] * $req_rows * $row_size;
                $buf = file_get_contents(Storage::disk('local')->path('t_'. $this->makeFileName($code, $fh['fid'])), FALSE, NULL, $read_pos, $req_rows * $row_size);
                $ar1 = explode(PHP_EOL, $buf);
                $p1 = array_pop($ar1);
            }
            
            // Remainder read
            if ($files[$fi]['remain_rows'] > 0) {
                $read_pos = ($files[$fi]['start_splits'] + $files[$fi]['end_splits']) * $req_rows * $row_size;
                $buf = file_get_contents(Storage::disk('local')->path('t_'. $this->makeFileName($code, $fh['fid'])), FALSE, NULL, $read_pos, $files[$fi]['remain_rows'] * $row_size);
                $ar2 = explode(PHP_EOL, $buf);
                $p2 = array_pop($ar2);
                $files[$fi]['remain_rows'] = 0;
            }
            
            // Merge and reset the array
            $bar = array_merge($bar, $ar, $ar1, $ar2);

            $ar = $ar1 = $ar2 =[];
        } // EOF foreach()
        
        if (count($bar) > 0) {
            shuffle($bar);
            return ['bar'=>$bar, 'files' => $files];
        } else {
            return null;
        }
    }

//// Helpers
    /**
     * Generate zip helper function
     */
    public function genZipFilesHelper($setting)
    {
        $cc = 0;
        
        $nfiles_in_zip = 10;
        $code = DB::table('codes')->where('is_ready', 3)->first();
        
        if (($code != null) && ($setting['run_zip'] == 1)) {
            $cc = 1;
            
            $gen_progress = unserialize($code->gen_progress);
            
            // Work on how many zips
            if (!isset($gen_progress['zfiles'])) {
                $remainder = $gen_progress['cfile'] % $nfiles_in_zip;
                $nzip = ($gen_progress['cfile'] - $remainder) / $nfiles_in_zip;
                
                $zfiles = [];
                $s = $e = 1;
                for ($i = 1; $i <= $nzip; $i++) {
                    $e = $i * $nfiles_in_zip;
                    $zfiles[$i] = ['s' => $s, 'e' => $e, 'p' => 0];
                    $s = $e+1;
                }
                
                if ($remainder > 0) {
                    if ($nzip > 0) {
                        $e = $e + $remainder;
                    } else {
                      $e = $remainder;
                    }
                    $zfiles[$i] = ['s' => $s, 'e' => $e, 'p' => 0];
                }
                
                $gen_progress['zfiles'] = $zfiles;
            }
            
            // make zip
            $z = 0;
            foreach ($gen_progress['zfiles'] as $fid => $zfile) {
                if ($zfile['p'] == 0) {
                    $z++; // has work to do.
                    $zipfile = storage_path('app/' . $code->file_prefix . '_' . $code->id . '_' . $fid . '.zip');
                    $zip = new \ZipArchive();
                    if ($zip->open($zipfile, \ZipArchive::CREATE)== TRUE) {
                        for($c = $zfile['s']; $c <= $zfile['e']; $c++) {
                            $codefile = storage_path('app/' . $this->makeFileName($code, $c));
                            if (! $zip->addFile($codefile, basename($codefile))) {
                                dd("errro");
                            }
                        }
                    }
                    $zip->close();
                    $gen_progress['zfiles'][$fid]['p'] = 1;
                    break;
                }
            }
            
            // update progress
            $is_ready = $code->is_ready;
            if ($z == 0) {
                $is_ready = $code->is_ready + 1;
            }
            
            DB::table('codes')->where('id',$code->id)->update(['is_ready' => $is_ready, 'gen_progress' => serialize($gen_progress)]);
        }
        
        return ['run' => $cc];
    }
    
    /**
     * Generate random codes helper function
     */
    public function genRandomCodesHelperOld($setting)
    {
        $cc = 0;
        $debug = 0;
        $is_debug = 0;
        $limit_file_row = $setting['limit_file_row'];
        
        $code = DB::table('codes')->where('is_ready', 2)->first();
        
        
        
        if (($code != null) && ($setting['run_random'] == 1)) {
            $gen_progress = unserialize($code->gen_progress);
            $files = unserialize($code->files);
            
            if (!isset($gen_progress['nfile'])) {
                $gen_progress['nfile'] = 1;
                $gen_progress['nrow'] = 0;
            }
            
            $ret_bar = 0;
            for ($i = 0; $i < 10; $i++) {
                $ret = $this->runCodeRandom($code, $limit_file_row, $gen_progress, $files, $i, $is_debug);
                
                if ($ret == null) {
                    DB::table('codes')->where('id',$code->id)->update(['is_ready'=>3]);
                    
                    // Delete all generated files
                    if (!$files) {
                        $files = $this->genFiles($limit_file_row);
                    }
                    $this->deleteFiles($code, $files);
                    
                    break;
                }
                $files = $ret['files'];
                
                // Debug
                if ($debug == 1) {
                    $gen_progress['debug'][$i]['ret'] = $ret['debug'];
                }
                
                // Write new files
                $newfilename = $this->makeFileName($code, $gen_progress['nfile']);
                $gen_progress['nrow'] = $gen_progress['nrow'] + count($ret['bar']);
                
                if ($gen_progress['nrow'] >= $limit_file_row) {
                    if ($gen_progress['nrow'] > $limit_file_row) {
                        // cut to limit_file_row
                        $current_rows = count($ret['bar']);
                        $exist_rows = $gen_progress['nrow'] - $current_rows;
                        
                        // require rows that need to make one million
                        $req_rows = $limit_file_row - $exist_rows;
                        $req_strarr = array_slice($ret['bar'], 0, $req_rows);
                        $req_str = implode(PHP_EOL, $req_strarr);
                        
                        
                        // Debug
                        if ($debug == 1) {
                            $gen_progress['debug'][$i]['a']['nrow'] = $gen_progress['nrow'];
                            $gen_progress['debug'][$i]['a']['nfile'] = $gen_progress['nfile'];
                            $gen_progress['debug'][$i]['a']['newfilename'] = $newfilename;
                            $gen_progress['debug'][$i]['a']['write_rows'] = $req_rows;
                            $gen_progress['debug'][$i]['a']['cbar'] = $ret_bar;
                        } else {
                            File::append(Storage::disk('local')->path($newfilename), $req_str.PHP_EOL);
                        }
                        
                        
                        // remain rows that need to make new files
                        $ret['bar'] = array_slice($ret['bar'], $req_rows, (count($ret['bar']) - $req_rows));
                        $gen_progress['nfile']++;
                        $gen_progress['nrow'] = $current_rows - $req_rows;
                        
                        
                    } else {
                        $req_str = implode(PHP_EOL, $ret['bar']);
                        $newfilename = $this->makeFileName($code, $gen_progress['nfile']);
                        
                        
                        // Debug
                        if ($debug == 1) {
                            $gen_progress['debug'][$i]['b']['nrow'] = $gen_progress['nrow'];
                            $gen_progress['debug'][$i]['b']['nfile'] = $gen_progress['nfile'];
                            $gen_progress['debug'][$i]['b']['newfilename'] = $newfilename;
                            $gen_progress['debug'][$i]['b']['write_rows'] = count($ret['bar']);
                            $gen_progress['debug'][$i]['b']['cbar'] = $ret_bar;
                        } else {
                            File::append(Storage::disk('local')->path($newfilename), $req_str.PHP_EOL);
                        }
                        
                        $gen_progress['nfile']++;
                        $gen_progress['nrow'] = 0;
                        
                        
                    }
                } // EOF if ($gen_progress['nrow'] >= $limit_file_row)
                
                if ($gen_progress['nrow'] > 0) {
                    $req_str = implode(PHP_EOL, $ret['bar']);
                    $newfilename = $this->makeFileName($code, $gen_progress['nfile']);
                    
                    
                    // Debug
                    if ($debug == 1) {
                        $gen_progress['debug'][$i]['c']['nrow'] = $gen_progress['nrow'];
                        $gen_progress['debug'][$i]['c']['nfile'] = $gen_progress['nfile'];
                        $gen_progress['debug'][$i]['c']['newfilename'] = $newfilename;
                        $gen_progress['debug'][$i]['c']['write_rows'] = count($ret['bar']);
                        $gen_progress['debug'][$i]['c']['cbar'] = $ret_bar;
                    } else {
                        File::append(Storage::disk('local')->path($newfilename), $req_str.PHP_EOL);
                    }
                }
            } // EOF for ($i = 0; $i < 10; $i++)
            
            // Debug
            if ($debug == 1) {
                dd($gen_progress['debug']);
            }
            
            // update progress
            DB::table('codes')->where('id',$code->id)->update(['files' => serialize($files), 'gen_progress' => serialize($gen_progress)]);
            
            // Stop here not to run below again
            dd("DONE Randomizing");
        }
        
        if ($is_busy == 1) {
            return true;
        }
    }
    
    /**
     * Generate random codes helper function
     */
    public function genRandomCodesHelper($setting)
    {
        $cc = 0;
        $code = DB::table('codes')->where('is_ready', 2)->first();
        if ($code != null) {
          $cc = 1;
          DB::table('codes')->where('id',$code->id)->update(['is_ready'=>3]);
        }
        
        return ['run' => $cc];
    }
    
    /**
     * Generate codes helper function
     */
    public function genCodesHelper($setting)
    {
        $cc = 0;
        $limit_file_row = $setting['limit_file_row'];
        $row = DB::table('codes')->where('is_ready', 1)->first();
        
        if (($row != null) && ($setting['run_gen'] == 1)) {
            $format_data = unserialize($row->format_data);
            $format_data['cdiff'] = $row->cdiff;
            $format_data['code_id'] = $row->id;
            if ($row->gen_progress == null) {
                $format_data['gen_progress'] = [
                    'ix1' => 0,
                    'ix2' => 0,
                    'in1' => 0,
                    'in2' => 0,
                    'in3' => 0,
                    'ix3' => 0,
                    'in4' => 0,
                    'in5' => 0,
                    'in6' => 0,
                    'ix4' => 0,
                    'crow' => 0,
                    'cfile' => 1
                ];
            } else {
                $format_data['gen_progress'] = unserialize($row->gen_progress);
            }
            
            // Countings
            $mn4 = count($format_data['n4']);
            $mn5 = count($format_data['n5']);
            $mn6 = count($format_data['n6']);
            $mx4 = count($format_data['x4']);
            
            //Debug
            // dd("Hello I am here");
            
            for ($i=0; $i < 8; $i++) {
                $cc++;
                
                $ret = $this->generateCodes($format_data);

                $format_data['cdiff'] = $ret['cdiff'];
                $format_data['gen_progress'] = $ret['gen_progress'];
                
                $x1 = $format_data['x1'];
                $x1_code = $this->makeStrTwoDigits($x1);
                
                // make next file if reach limit file rows
                if ($format_data['gen_progress']['crow'] >= $limit_file_row) {
                    if ($format_data['gen_progress']['crow'] > $limit_file_row) {
                        // cut to limit_file_row
                        $stra = explode(PHP_EOL, $ret['str']);
                        $current_rows = count($stra) - 1;
                        $exist_rows = $format_data['gen_progress']['crow'] - $current_rows;
                        
                        // require rows that need to make one million
                        $req_rows = $limit_file_row - $exist_rows;
                        $req_strarr = array_slice($stra, 0, $req_rows);
                        $req_str = implode(PHP_EOL, $req_strarr);
                        $file_name = $row->file_prefix . $this->makeFileDigits($row->id) . '_' . $this->makeFileDigits($format_data['gen_progress']['cfile']) . '.txt';
                        File::append(Storage::disk('local')->path($file_name), $req_str.PHP_EOL);
                        $this->insertDB($format_data, $ret['db'], $row->id);
                        
                        // remain rows that need to make new files
                        $remain_strarr = array_slice($stra, $req_rows, (count($stra) - $req_rows));
                        $ret['str'] = implode(PHP_EOL, $remain_strarr);
                        
                        $files[] = ['fid' => $i,'total_row' => 16];
                        
                        $format_data['gen_progress']['cfile']++;
                        $format_data['gen_progress']['crow'] = $current_rows - $req_rows;
                    } else {
                        $file_name = $row->file_prefix . 
                                     $this->makeFileDigits($row->id) . '_' . 
                                     $this->makeFileDigits($format_data['gen_progress']['cfile']) . '.txt';
                        File::append(Storage::disk('local')->path($file_name), $ret['str']);
                        $this->insertDB($format_data, $ret['db'], $row->id);
                        $ret['str'] = '';
                        $format_data['gen_progress']['cfile']++;
                        $format_data['gen_progress']['crow'] = 0;
                    }
                }
                
                $file_name = $row->file_prefix . 
                             $this->makeFileDigits($row->id) . '_' . 
                             $this->makeFileDigits($format_data['gen_progress']['cfile']) . '.txt';
                // Check if null, codes are generated
                if (($ret == null) || ($ret['cdiff'] >= $format_data['ndiff'])) {
                    if (strlen($ret['str']) > 0) {
                        File::append(Storage::disk('local')->path($file_name), $ret['str']);
                        $this->insertDB($format_data, $ret['db'], $row->id);
                    }
                    
                    DB::table('codes')->where('id',$row->id)->update(['is_ready'=>2]);
                    break;
                } else {
                    if ($format_data['gen_progress']['crow'] > 0) {
                        File::append(Storage::disk('local')->path($file_name), $ret['str']);
                        $this->insertDB($format_data, $ret['db'], $row->id);
                    }
                }
            } // EOF for()
            
            // update code finally
            DB::table('codes')->where('id',$row->id)->update(['cdiff'=>$format_data['cdiff'], 'gen_progress'=>serialize($format_data['gen_progress'])]);
        } // EOF if ($row)
        
        return ['run' => $cc];
    }
/////

    /**
     *
     */
    public function runCodeGenerator($id, $is_cron, $is_debug)
    {
        // Load settings
        $ret = [];
        $setting = ['limit_file_row' => 1000000, 'is_busy' => 0, 'is_dev'=>1, 'run_gen'=>1, 'run_random'=>1, 'run_zip'=>1];
        $setting_db = DB::table('code_settings')->where('code_id', 1)->first();
        if ($setting_db != null) {
            $setting = ['limit_file_row' => $setting_db->limit_file_row, 'is_busy'=>$setting_db->is_busy, 'is_dev'=>$setting_db->is_dev, 'run_gen'=>$setting_db->run_gen, 'run_random'=>$setting_db->run_random, 'run_zip'=>$setting_db->run_zip];
        }

        // Generate zip files
        $ret = $this->genZipFilesHelper($setting);
        if ($ret['run'] > 0) {
          dd("Done generate zip files");
        }

        // Generate random codes
        $ret = $this->genRandomCodesHelper($setting);
        if ($ret['run'] > 0) {
          dd("Done Generate Random codes");
        }

        // Generate codes
        $ret = $this->genCodesHelper($setting);
        if ($ret['run'] > 0) {
          dd("Done generate codes");
        }
        

        dd("DONE", $ret);
        
    } //EOF

}
