<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Traits\UploadFile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TourPackageController extends Controller
{
    use UploadFile;
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
                'category_id'   => 'required|integer|exists:categories,id',
                'feature_image' => ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:12048'],
                'gallery_images' => ['nullable', 'array', 'max:20', function($attribute, $value, $fail) use($request){
                    if (!$request->file('gallery_images')){
                        $fail('Allow only file type');
                    }
                }],
//                'gallery_images.*' => ['image', ]  array multiple datat type validation


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
