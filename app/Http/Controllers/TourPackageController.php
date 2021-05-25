<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Presenter\TourPackagePresenter;
use App\Traits\UploadFile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use function PHPUnit\Framework\returnArgument;

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
            $data           = $this->validate($request, [
                'package_title'    => 'required|string|max:255',
                'place_name'       => 'required|string|max:255',
                'duration'         => 'required|string|max:255',
                'description'      => 'nullable|string',
                'category_id'      => 'required|integer|exists:categories,id',
                'feature_image'    => ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:12048'],
                //                'gallery_images' => ['nullable', 'array', 'max:20', function ($attribute, $value, $fail) use ($request) {
                //                    if (!$request->file('gallery_images')) {
                //                        $fail('Allow only file type');
                //                    }
                //
                //                }],
                'gallery_images'   => ['nullable', 'array', 'max:20'],
                'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:12048']


            ]);
            $feature_image  = tap(self::uploadSingleImage($data['feature_image'], config('fastbooking.tour_package_image.original'), 'public', true, 200), function ($value) {
                return $value['file_name'];
//                $feature_image =  config('fastbooking.tour_package_image.base_path').config('fastbooking.tour_package_image.original').$value['file_name'];
            });
            $gallery_images = [];
            foreach ($data['gallery_images'] as $gallery) {
                $uploaded         = $this->uploadSingleImage($gallery, config('fastbooking.tour_package_image.original'), 'public', true);
                $gallery_images[] = [
                    'original' => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $uploaded['file_name'],
                    'thumb'    => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.thumb') . $uploaded['file_name'],
                ];
            }

            TourPackage::create([
                'category_id'   => $data['category_id'],
                'title'         => $data['package_title'],
                'place_name'    => $data['place_name'],
                'duration'      => $data['duration'],
                'descriptions'  => $data['description'],
                'feature_image' => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $feature_image['file_name'],
                'gallery'       => json_encode($gallery_images, true),
            ]);
            return response()->json('Package Created', 200);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (QueryException | \Exception $e) {
            dd($e->getMessage());
            return response()->json('Something went wrong!', 406);
        }
    }

    public function allTourPackage()
    {
        try {
            $selected_fields = [
                'id', 'category_id', 'place_name', 'duration', 'feature_image', 'gallery', 'descriptions', 'title'
            ];

            $tour_packages = TourPackage::select($selected_fields)
                ->with(['category' => function ($category) {
                    $category->select('id', 'name');
                }])
                ->orderBy('place_name', 'asc')
                ->get();

            return TourPackagePresenter::fetchList($tour_packages);

//            return response()->json([
//                'packages' => $tour_packages
//            ], 200);
        } catch (QueryException | \Exception $e) {
            dd($e->getMessage());
            return response()->json('Something went wrong!', 406);
        }


    }

    public function update(Request $request)
    {
        try {
            $validated     = $this->validate($request, [
                'package_id'      => 'required|integer|exists:tour_packages,id',
                'package_title'   => 'required|string|max:255',
                'place_name'      => 'required|string|max:255',
                'duration'        => 'required|string|max:255',
                'descriptions'    => 'nullable|string',
                'category_id'     => 'required|integer|exists:categories,id',
                'feature_image'   => 'nullable|image|mimes:jpg,jpeg,png,svg|max:10248',
                'gallery_images'   => 'nullable|array|max:20',
                'gallery_images.*' => 'image|mimes:jpg,jpeg,png,svg|max:10248',
            ]);
            $tourPackage   = TourPackage::find($validated['package_id']);
            $feature_image = [];
            if ($request->hasFile('feature_image')) {
                $feature_image = tap(self::uploadSingleImage($validated['feature_image'], config('fastbooking.tour_package_image.original'), 'public', true, 200), function ($value) {
                    return $value['file_name'];
//                $feature_image =  config('fastbooking.tour_package_image.base_path').config('fastbooking.tour_package_image.original').$value['file_name'];
                });
            }
            $gallery_images = [];
            foreach ($validated['gallery_images'] as $gallery) {
                $uploaded         = self::uploadSingleImage($gallery, config('fastbooking.tour_package_image.original'), 'public', true);
                $gallery_images[] = [
                    'original' => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $uploaded['file_name'],
                    'thumb'    => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.thumb') . $uploaded['file_name'],
                ];
            }
            $tourPackage->update([
                'title'         => $validated['package_title'],
                'place_name'    => $validated['place_name'],
                'duration'      => $validated['duration'],
                'descriptions'  => $validated['descriptions'],
                'category_id'   => $validated['category_id'],
                'feature_image' => array_key_exists('file_name', $feature_image) ? config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $feature_image['file_name'] : $tourPackage->feature_image,
                'gallery'       => count($gallery_images) > 0 ? json_encode($gallery_images, true) : $tourPackage->gallery,
            ]);
            return response()->json('update successful', 204);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (QueryException | \Exception $e) {
            dd($e->getMessage());
            return response()->json('Something went wrong', 406);
        }
    }
}
