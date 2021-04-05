<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CategoryController extends Controller
{

    public function index(Inertia $inertia)
    {
//        $categories = Category::whereNull('parent_id')->get();
        return $inertia::render('Category/Index');
    }

    public function store(Request $request)
    {
        try {
            $data = $this->validate($request, [
                'name' => 'required|string|max:255'
            ]);
            Category::create([
                'name' => $data['name']
            ]);
            return response()->json('Category created', 200);
        }catch (ValidationException $exception){

            return response()->json($exception->errors(), 422);
        }
        catch (QueryException|\Exception $ex){
            return response()->json([], 406);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $this->validate($request, [
                'name' => 'required|string|max:255'
            ]);
            $category = Category::find($id);
            $category->update([
                'name' => $data['name']
            ]);
            return response()->json('success', 204);
        }catch (ValidationException $exception){
            return response()->json($exception->errors(), 422);
        }catch (QueryException|\Exception $exception){
            return response()->json('Something went wrong', 406);
        }
    }

    public function delete($id)
    {
        try{
            $category = Category::find($id);
            $category->destroy($id);
            return response()->json('Category Deleted', 200);
        }catch (QueryException|\Exception $exception){
            return response()->json([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'line' => $exception->getLine()
            ], 406);
        }
    }

    public function selectedDelete(Request $request)
    {
        try{
            $data = $this->validate($request, [
               'ids' => 'required|array',
               'ids.*' => 'required|exists:categories,id'
            ]);
            Category::whereIn('id', $data['ids'])->delete();
            return response()->json('Category Deleted', 200);
        }catch (QueryException|\Exception $exception){
            return response()->json([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'line' => $exception->getLine()
            ], 406);
        }
    }

    public function allCategory()
    {
        $categories = Category::whereNull('parent_id')->get();
        return response()->json($categories, 200);
    }
}
