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

    }

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {

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
}