<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TourPackageController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('TourPackage/Index');
    }

    public function store(Request $request)
    {
        try {
            $data = $this->validate($request, [
                'package_title' => 'required|string|max:255',
                'place_name'    => 'required|string|max:255',
                'duration'      => 'required|string|max:255',
                'description'   => 'nullable|string',
                'category_id'   => 'required|integer|exists:categories,id'
            ]);
            TourPackage::create([
                'category_id' => $data['category_id'],
                'title'       => $data['package_title'],
                'place_name'  => $data['place_name'],
                'duration'    => $data['duration'],
                'descriptions' => $data['description'],
            ]);
            return response()->json('Package Created', 200);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (QueryException | \Exception $e) {
            dd($e->getMessage());
            return response()->json('Something went wrong!', 406);
        }
    }
}
