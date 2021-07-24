<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('super_admin_check:store-update-destroy');
    }

    public function index(Request $request)
    {
        $query = Category::with('parent');
        $categories = $this->filterAndResponse($request, $query);
        return response()->json([
            'categories' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only('title'), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => 'Please fix these errors', 
                'errors' => $validator->errors()
            ], 500);
        }

        $category = new Category();
        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id') != '' ? $request->input('parent_id') : null;
        $category->featured = $request->input('featured');
        $category->save();
        $this->insertFeatures($request, $category);
        return response()->json([
            'success' => true, 
            'message' => 'Created successfully', 
            'category' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::with('parent', 'features')->findOrFail($id);
        return response()->json([
            'category' => $category
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::with('parent')->findOrFail($id);
        $validator = Validator::make($request->only('title'), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => 'Please fix these errors', 
                'errors' => $validator->errors()
            ], 500);
        }

        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id') != '' ? $request->input('parent_id') : null;
        $category->featured = $request->input('featured');
        $category->save();
        $category->features()->delete();
        $this->insertFeatures($request, $category);
        return response()->json([
            'success' => true, 
            'message' => 'Updated successfully', 
            'category' => $category
        ], 200);
    }

    public function destroy($id)
    {

    }

    protected function filterAndResponse(Request $request, \Illuminate\Database\Eloquent\Builder $query)
    {
        if ($request->filter_by_id) {
            $query->where('id', $request->filter_by_id);
        }if ($request->filter_by_title) {
            $query->where('title', 'like', "%" . $request->filter_by_title . "%");
        }if ($request->filter_by_parent_id) {
            $query->where('parent_id', $request->filter_by_parent_id);
        }

        $categories = $query->paginate(10);
        return $categories;
    }

    protected function insertFeatures($request, $category) {
        if($request->has('features')) {
            foreach ($request->input('features') as $feature) {
                if(!empty($feature["field_title"])) {
                    $categoryFeature = new CategoryFeature();
                    $categoryFeature->field_title = $feature["field_title"];
                    $categoryFeature->field_type = $feature["field_type"];
                    $category->features()->save($categoryFeature);
                }
            }
        }
    }
}