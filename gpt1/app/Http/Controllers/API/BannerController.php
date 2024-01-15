<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Banner;
use Validator;

class BannerController extends Controller
{
    public $successStatus = 200;
    public $successCreated = 201;

    public function addNewBanner(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'target_url'  => 'required',
            'expiry_date' => 'required|date' 
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message
                ],
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        try{
            $banner = new Banner;
            $banner->title = $request->input('title');
            $banner->description = $request->input('description');
            if($request->hasFile('image')){
                $image_name = time().'.'.request()->image->getClientOriginalExtension();
                $path = $request->file('image')->move(public_path('/uploads/images/banners'), $image_name);
                $banner->image = $image_name;
            }
            $banner->target_url = $request->input('target_url');
            $banner->expiry_date = date('Y-m-d', strtotime($request->input('expiry_date')));
            $banner->save();

            $message = 'Upload banner successfully';
            $response = [
                'status' => true,
                'msg' => $message,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message,
                ],
                'data' => $banner      
            ];
            return response()->json($response, $this->successCreated);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'msg' => $message,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message,
                ],
                'data' => [],
            ];
            return response()->json($response, 401);
        } 
    }
}
