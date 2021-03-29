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
    public function index()
    {
//        $categories = Category::whereNull('parent_id')->get();
        return Inertia::render('Category/Index');
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

    public function update(Request $id)
    {

    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->destroy($id);
        $categories = Category::whereNull('parent_id')->get();
        return Inertia::render('Category/Index', [
            'categories' => $categories
        ]);
    }

    public function allCategory()
    {
        $categories = Category::whereNull('parent_id')->get();
        return response()->json($categories, 200);
    }
}
