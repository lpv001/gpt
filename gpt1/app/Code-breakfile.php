<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use App;
use DB;

class Code extends Model
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

        //dd('KKK');
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
        shuffle($x2s);
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
            return '00000' . $num;
        }
        if ($num < 100) {
            return '0000' . $num;
        }
        if ($num < 1000) {
            return '000' . $num;
        }
        if ($num < 10000) {
            return '00' . $num;
        }
        if ($num < 100000) {
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
                      22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z'];
                      
        $alphabet_x = array_flip($alphabets);
        $num = $alphabet_x[$alp];
        $rstr = '';
        
        if ($num < 10) {
            $rstr = '0' . $num;
        }
        
        return (string)$rstr;
    }

   /**
    *
    */
    public function generateCodes($format_data)
    {
        //dd("generateCodes", $format_data);
        
        $data = [];
        $str_data = '';
        $c = $format_data['cdiff'];
        $i = 0;
        
        $debug = [];
        
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
        
        $limit_file_row = 1000000 - ($mn4 * $mn5 * $mn6 * $mx4);
        
        for ($ix1 = $gen_progress['ix1']; $ix1 < $mx1; $ix1++) {
            if ($i > 0) {
                $gen_progress['ix2'] = 0;
            }
            $gen_progress['ix1'] = $ix1;
            $x1 = $format_data['x1'][$ix1];
            //$debug[] = 'x1:' . $gen_progress['ix1'] . ':' . $ix1 . '=>' . $x1 . PHP_EOL;
            
            for ($ix2 = $gen_progress['ix2']; $ix2 < $mx2; $ix2++) {
                if ($i > 0) {
                    $gen_progress['in1'] = 0;
                }
                $gen_progress['ix2'] = $ix2;
                $x2 = $format_data['x2'][$ix2];
                //$debug[] = 'x2:' . $gen_progress['ix2'] . ':' . $ix2 . '=>' . $x2 . PHP_EOL;
                
                for ($in1 = $gen_progress['in1']; $in1 < $mn1; $in1++) {
                    if ($i > 0) {
                        $gen_progress['in2'] = 0;
                    }
                    $gen_progress['in1'] = $in1;
                    $n1 = $format_data['n1'][$in1];
                    //$debug[] = ' n1:' . $gen_progress['in1'] . ':' . $in1 . '=>' . $n1 . PHP_EOL;
                    
                    for ($in2 = $gen_progress['in2']; $in2 < $mn2; $in2++) {
                        if ($i > 0) {
                            $gen_progress['in3'] = 0;
                        }
                        $gen_progress['in2'] = $in2;
                        $n2 = $format_data['n2'][$in2];
                        //$debug[] = '  n2:' . $gen_progress['in2'] . ':' . $in2 . '=>' . $n2 . PHP_EOL;
                        
                        for ($in3 = $gen_progress['in3']; $in3 < $mn3; $in3++) {
                            if ($i > 0) {
                              $gen_progress['ix3'] = 0;
                            }
                            $gen_progress['in3'] = $in3;
                            $n3 = $format_data['n3'][$in3];
                            //$debug[] = '   n3:' . $gen_progress['in3'] . ':' . $in3 . '=>' . $n3 . PHP_EOL;
                            
                            for ($ix3 = $gen_progress['ix3']; $ix3 < $mx3; $ix3++) {
                                $gen_progress['ix3'] = $ix3;
                                $x3 = $format_data['x3'][$ix3];
                                //$debug[] = '    x3:' . $gen_progress['ix3'] . ':' . $ix3 . '=>' . $x3 . PHP_EOL;
                                
                                if ($i > $nc || ($gen_progress['crow'] > $limit_file_row)) {
                                    //dd("debug kk",$c, $i, $format_data, $gen_progress, $debug);
                                    return ['data' => $data, 'str' => $str_data, 'cdiff' => $c, 'gen_progress' => $gen_progress];
                                }
                                //dd($gen_progress);
                                foreach ($format_data['n4'] as $n4) {
                                    foreach ($format_data['n5'] as $n5) {
                                        foreach ($format_data['n6'] as $n6) {
                                            foreach ($format_data['x4'] as $x4) {
                                                $str_format = $x1. $x2. $n1 . $n2. $n3 . $x3 . $n4 . $n5 . $n6 . $x4;
                                                $data[$ix2][] = ['code_id' => $format_data['code_id'], 'data' => $str_format];
                                                $str_data .= $str_format . PHP_EOL;
                                                $c++;
                                                $i++;
                                                $gen_progress['crow']++;
                                                //echo $c . ':';
                                                //dd($gen_progress);
                                            }
                                        }
                                    }
                                }
                                
                                //echo PHP_EOL;
                            }
                        }
                    } // EOF for ($in2...)
                } // EOF for ($in1...)
            } // EOF for ($ix2...)
        } // EOF for ($ix1...)
        
        if (count($data) > 0) {
            //dd("debug jj",$c, $i, $format_data, $gen_progress, $debug);
            return ['data' => $data, 'str' => $str_data, 'cdiff' => $c, 'gen_progress' => $gen_progress];
        } else {
            return null;
        }
    } // EOF

    /**
     *
     */
    public function insertDB($format_data, $data)
    {
        $x1 = $format_data['x1'];
        $x2s = $format_data['x2'];
        
        foreach ($data as $ix2 => $d) {
            $x2 = $format_data['x2'][$ix2];
            $codenumber = $this->makeStrTwoDigits($x1) . $this->makeStrTwoDigits($x2);
            $table_name = 'code_data'.$codenumber;
            
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
    public function runCodeGenerator()
    {
        $row = DB::table('codes')->where('is_ready', 1)->first();
        
        $file_name = Storage::disk('local')->path('datafile07_000000.txt');
        if (($handle = fopen($file_name, "r+")) !== FALSE) {
            $contents = fread($handle, 14);
            $ft = ftell($handle);
            ftruncate($handle, 0);
            fclose($handle);
            
            dd(filesize($file_name), $ft, $contents);
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                dd($data);
                $row++;
                
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    } //EOF

    /**
     *
     */
    public function runCodeGeneratorBK()
    {
        $row = DB::table('codes')->where('is_ready', 1)->first();
        
        if ($row != null) {
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
                    'cfile' => 0
                ];
            } else {
                $format_data['gen_progress'] = unserialize($row->gen_progress);
            }
            
            // Countings
            $mn4 = count($format_data['n4']);
            $mn5 = count($format_data['n5']);
            $mn6 = count($format_data['n6']);
            $mx4 = count($format_data['x4']);
            $limit_file_row = 1000000 - ($mn4 * $mn5 * $mn6 * $mx4);
            
            $debugs = [];
            
            for ($i=0; $i < 24; $i++) {
                //dd($limit_file_row, $format_data);
                $ret = $this->generateCodes($format_data);
                $format_data['cdiff'] = $ret['cdiff'];
                $format_data['gen_progress'] = $ret['gen_progress'];
                
                // make next file if reach limit file rows
                if ($format_data['gen_progress']['crow'] >= $limit_file_row) {
                    //dd("Rows reach limit", $limit_file_row, $format_data['gen_progress']['crow'], $format_data);
                    $format_data['gen_progress']['cfile']++;
                    $format_data['gen_progress']['crow'] = 0;
                }
                
                // Check if db table not existed create one
                $x1 = $format_data['x1'];
                $file_name = $row->file_prefix . $this->makeStrTwoDigits($x1) 
                             . '_' . $this->makeFileDigits($format_data['gen_progress']['cfile']) . '.txt';
                
                // Check if null, codes are generated
                if (($ret == null) || ($ret['cdiff'] >= $format_data['ndiff'])) {
                    if (count($ret['data']) > 0) {
                        //$debugs[] = ['i' => $i, 'cdiff' => $ret['cdiff'], 'cdata' => count($ret['data']), 'gen_progress' => $ret['gen_progress']];
                        $this->insertDB($format_data, $ret['data']);
                        File::append(Storage::disk('local')->path($file_name), $ret['str']);
                    }
                    
                    DB::table('codes')->where('id',$row->id)->update(['is_ready'=>2]);
                    break;
                } else {
                    //$debugs[] = ['i' => $i, 'cdiff' => $ret['cdiff'], 'cdata' => count($ret['data']), 'gen_progress' => $ret['gen_progress']];
                    $this->insertDB($format_data, $ret['data']);
                    File::append(Storage::disk('local')->path($file_name), $ret['str']);
                }
                
                //dd("debug End", $format_data);
                //sleep(1);
            } // EOF for()
            
            // update code finally
            DB::table('codes')->where('id',$row->id)->update([
                                                              'cdiff'=>$format_data['cdiff'],
                                                              'gen_progress'=>serialize($format_data['gen_progress'])
                                                             ]);
            
            //dd("Debugs", $limit_file_row, $debugs, $format_data);
        } // EOF if ($row)
        
        dd("DONE RunnerCode");
    } //EOF
    
}
