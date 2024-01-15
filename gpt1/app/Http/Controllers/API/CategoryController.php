<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Resources\CategoryCollectionResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoriesCollectionResource;
use App\Http\Resources\CategoriesResource;
use App\Product;
use Validator;
use DB;

class CategoryController extends Controller
{
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * Display list all of category
     * 
     */
    public function getCategoryList()
    {
        $category = new Category;
        $categories = $category->getCategories(0);
        
        return (new CategoriesCollectionResource($categories))->response();
    } //EOF

    /**
     * Display list all of category
     * 
     */
    public function getCategories()
    {
        $categories = Category::with([
            'sub_category' => function ($query) {
                return $query->select('id', 'parent_id', 'default_name', 'image_name');
            },
        ])->get();

        return (new CategoriesCollectionResource($categories))->response();
    } //EOF

    /**
     * Display Detail of category
     * 
     */
    public function getDetailCategory($category_id)
    {
        $category = new Categories;
        $category = $category->getDetailofCategory($category_id);

        $message = 'Get Category detail successfully';
        $response = [
            'status'  => true,
            'msg' => $message,
            'data' => [
                'Category' => $category
            ]
        ];
        return response()->json($response, $this->successStatus);
    } //EOF

    /**
     * Add new category 
     */
    public function addNewCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|numeric',
            'lft'  => 'required|numeric',
            'rgt'  => 'required|numeric',
            'depth'  => 'required|numeric',
            'default_name' => 'required',
            'slug' => 'required',
            'image_name'  => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        try {
            $category = new Categories;
            $category->parent_id = $request->input('parent_id');
            $category->lft = $request->input('lft');
            $category->rgt = $request->input('rgt');
            $category->depth = $request->input('depth');
            $category->default_name = $request->input('default_name');
            $category->slug = $request->input('slug');
            if ($request->hasFile('image_name')) {
                $image_name = time() . '.' . request()->image_name->getClientOriginalExtension();
                $path = $request->file('image_name')->move(public_path('/uploads/images/categories'), $image_name);
                $category->image_name = $image_name;
            }
            $category->save();

            $message = 'Upload category successfully';
            $response = [
                'status' => true,
                'msg' => $message,
                'data' => $category
            ];
            return response()->json($response, $this->successCreated);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Display list of product by category id
     * 
     */
    public function getProductCategory($category_id)
    {
        try {
            $category = new Categories;
            $categories = $category->getProductbyCategory($category_id);

            $message = __('products.get_product_success');
            $response = [
                'status'  => true,
                'msg' => $message,
                'data' => [
                    'product' => $categories
                ]
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     *
     */
    public function show(Request $request, $id)
    {
        $category = Category::where('id', $id)->get();
        
        return (new CategoryCollectionResource($category))->request([
            'limit' => $request->input('limit', 12),
            'page' => $request->input('page', 1)
        ])->response();
    }
}
