<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Category/Index');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

        try {
            Category::create([
                'name' => $data['name']
            ]);
            return Inertia::render('Category/Index');
        }catch (QueryException|\Exception $ex){
            return response()->json([], 406);
        }
    }
}
