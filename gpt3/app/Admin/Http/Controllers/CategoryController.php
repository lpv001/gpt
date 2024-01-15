<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateCategoryRequest;
use App\Admin\Http\Requests\UpdateCategoryRequest;
use App\Admin\Repositories\CategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\Category;
use App\Admin\Models\CategoryTranslation;
use Yajra\DataTables\DataTables;

class CategoryController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->path = public_path('/uploads/images/categories');
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * 
     * 
     */
    public function generateThumbnails()
    {
        dd("DONE");
        $images = Category::where('updateflag', 0)->skip(0)->take(1000)->get();
        if (count($images) < 1) {
            dd("DONE");
        }
        
        foreach ($images as $key => $value) {
            $image_path = $this->path . '/' . $value->image_name;
            if (strlen($value->image_name) > 0) {
                if (file_exists($image_path)) {
                    createThumbnail($this->path, $value->image_name);
                }
            }
            Category::where('id', $value->id)->update(['updateflag' => 1]);
        }
        
        return true;
    }

    /**
     * Display a listing of the Category.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //$this->generateThumbnails();
        
        return view('admin::categories.index');
    }

    /**
     *
     */
    public function datatables(Request $request)
    {
        $categories = $this->categoryRepository->all();
        
        if ($request->ajax()) {
            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('image_name', function ($category) {
                    return $category->image_name ? '<img src="' . asset('/uploads/images/categories/thumbnail/small_' . $category->image_name) . '" alt="" srcset="" height="70">' : '<img src="https://www.villas4u.com/assets/img/image-not-found.svg" alt="" srcset="" height="70">';
                })
                ->editColumn('name_en', function ($category) {
                    return $category->name_en;
                })
                ->editColumn('parent', function ($category) {
                    $ret = '';
                    if ($category->parent_id > 0) {
                       $parents = $this->categoryRepository->all()->where('id', $category->parent_id);
                       foreach($parents as $p) {
                           $ret .= $p->default_name;
                       }
                       return $ret;
                    }
                    return $ret;
                })
                ->editColumn('sub_categories', function ($category) {
                    $ret = '';
                    $subs = $this->categoryRepository->all()->where('parent_id', $category->id);
                    $c = 0;
                    foreach($subs as $s) {
                        $ret .= (($c > 0) ? ' | ' : '') . $s->default_name;
                        $c++;
                    }
                    return $ret;
                })
                ->editColumn('is_active', function ($category) {
                    return $category->is_active == 1 ? '<button type="button" data-id="' . $category->id . '" data-type="active" class="btn btn-xs btn-success product-status">Enable</button>' :
                        '<button type="button" data-id="' . $category->id . '" data-type="in-active" class="btn btn-xs btn-danger product-status">Disable</button>';
                })
                ->addColumn('action', function ($category) {
                    return '<div class="btn-group">
                                <a href=' . route('categories.edit', [$category->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $category->id . '" class="btn btn-danger btn-xs btn-delete"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'image_name', 'is_active'])
                ->toJson();
        }
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {

        $categories = Category::pluck('default_name', 'id')->toArray();

        return view('admin::categories.create', compact('categories'));
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $image_name = '';
        if (isset($request->images)) {
            foreach ($request->images as $image) {
                $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $image->move($this->path, $filename);
                createThumbnail($this->path, $filename);
                $image_name = $filename;
            }
        }

        $input = [
            'parent_id' =>  $request->parent_id ?? 0,
            'default_name' =>  $request->default_name[0],
            'slug' =>  $request->slug ?? strtolower($request->default_name[0]),
            'order' =>  $request->parent_id ?? 0,
            'image_name' =>  $image_name ?? '',
            'is_active' =>  1,
        ];

        $category = $this->categoryRepository->create($input);

        $_name[0] = ['local' => 'en', 'name' => ''];
        $_name[1] = ['local' => 'km', 'name' => ''];
        $_name[2] = ['local' => 'cn', 'name' => ''];
        foreach ($request->lang as $item_key => $item) {
            if ($item === 'en') $_name[0]['name'] = $request->default_name[$item_key];
            if ($item === 'km') $_name[1]['name'] = $request->default_name[$item_key];
            if ($item === 'cn') $_name[2]['name'] = $request->default_name[$item_key];
        }

        $lang = ['en', 'km', 'cn'];
        foreach ($lang as $key => $value) {
            CategoryTranslation::create([
                'category_id'   => $category->id,
                'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
                'locale'     =>  $value,
            ]);
        }

        Flash::success('Category saved successfully.');

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('admin::categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);
        $category_translation = CategoryTranslation::where('category_id', $id)->pluck('name', 'locale');

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $edit = 1;

        $categories = Category::pluck('default_name', 'id')->toArray();

        return view('admin::categories.edit', compact('categories', 'category', 'edit', 'category_translation'));
    }

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->find($id);
        if (empty($category)) {
            Flash::error('Category not found');
            return redirect(route('categories.index'));
        }

        $image_name = '';

        if (isset($request->old)) {
            $image_name = $category->image_name;
        } else {
            if ($category->image_name != "") {
                $image_path = public_path('/uploads/images/categories/' . $category->image_name);
                if (file_exists($image_path)) {
                    unlink($image_path);
                    deleteThumbnail($this->path, $category->image_name);
                }
            }
        }

        if (isset($request->images)) {
            foreach ($request->images as $image) {
                $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $image->move($this->path, $filename);
                createThumbnail($this->path, $filename);
                $image_name = $filename;
            }
        }

        $input = [
            'parent_id' =>  intval($request->parent_id ?? 0),
            'default_name' =>  $request->default_name[0],
            'slug' =>  $request->slug ?? '',
            'order' =>  intval($request->parent_id ?? 0),
            'image_name' =>  $image_name ?? '',
            'is_active' =>  1,
        ];

        $category = $this->categoryRepository->update($input, $id);
        $_name[0] = ['local' => 'en', 'name' => ''];
        $_name[1] = ['local' => 'km', 'name' => ''];
        $_name[2] = ['local' => 'cn', 'name' => ''];

        foreach ($request->lang as $item_key => $item) {
            if ($item === 'en') $_name[0]['name'] = $request->default_name[$item_key];
            if ($item === 'km') $_name[1]['name'] = $request->default_name[$item_key];
            if ($item === 'cn') $_name[2]['name'] = $request->default_name[$item_key];
        }

        $lang = ['en', 'km', 'cn'];
        foreach ($lang as $key => $value) {
            CategoryTranslation::updateOrCreate([
                'category_id'  =>  $id,
                'locale'    =>  $value
            ], [
                'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
            ]);
            // CategoryTranslation::where('category_id', $id)->where('local', $value)->update([
            //     'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
            // ]);
        }

        Flash::success('Category updated successfully.');

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error('Category not found');
            return redirect(route('categories.index'));
        }

        if ($category->image_name != "") {
            $image_path = public_path('/uploads/images/categories/' . $category->image_name);
            if (file_exists($image_path)) {
                unlink($image_path);
                deleteThumbnail($this->path, $value->image_name);
            }
        }

        CategoryTranslation::where('category_id', $id)->delete();
        $this->categoryRepository->delete($id);

        return response()->json(['status'   =>  200, 'message'  => 'Category deleted sucessfully.']);
    }

    /**
     *
     */
    public function updateStatus(Request $request)
    {
        if ($request->ajax()) {
            Category::where('id', $request->id)->update(['is_active' => $request->status]);

            return response()->json(['status' => 200, 'message' => 'Category updated successfully.']);
        }
    }
}
