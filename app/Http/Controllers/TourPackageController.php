<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Models\TourPackageImage;
use App\Presenter\TourPackagePresenter;
use App\Traits\UploadFile;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            if (array_key_exists('gallery_images', $data)){
                foreach ($data['gallery_images'] as $gallery) {
                    $uploaded         = $this->uploadSingleImage($gallery, config('fastbooking.tour_package_image.original'), 'public', true);
                    $gallery_images[] = [
                        'original' => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $uploaded['file_name'],
                        'thumb'    => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.thumb') . $uploaded['file_name'],
                    ];
                }
            }

            tap(TourPackage::create([
                'category_id'   => $data['category_id'],
                'title'         => $data['package_title'],
                'place_name'    => $data['place_name'],
                'duration'      => $data['duration'],
                'descriptions'  => $data['description'],
                'feature_image' => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $feature_image['file_name'],
//                'gallery'       => json_encode($gallery_images, true),
            ]), static function ($tour_package) use($gallery_images) {
                if (count($gallery_images) > 0){
                    foreach ($gallery_images as $gallery_image){
                        TourPackageImage::create([
                            'tour_package_id' => $tour_package->id,
                            'original' => $gallery_image['original'],
                            'thumbnail' => $gallery_image['thumb']
                        ]);
                    }
                }
            });
            return response()->json('Package Created', 200);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (QueryException | \Exception $e) {
            Log::info('update error', [
                'message' => $e->getMessage(),
                'filename' => $e->getFile(),
                'line no' => $e->getLine()
            ]);
            return response()->json('Something went wrong!', 406);
        }
    }

    public function allTourPackage()
    {
        try {
            $selected_fields = [
                'id', 'category_id', 'place_name', 'duration', 'feature_image', 'descriptions', 'title'
            ];

            $tour_packages = TourPackage::select($selected_fields)
                ->with([
                    'category' => function ($category) {
                    $category->select('id', 'name');
                }, 'images'
                    ])
                ->orderBy('place_name', 'asc')
                ->get();

            return TourPackagePresenter::fetchList($tour_packages);
//            return response()->json([
//                'packages' => $tour_packages
//            ], 200);
        } catch (QueryException | \Exception $e) {

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
                $feature_image = tap($this->uploadSingleImage(
                    $validated['feature_image'],
                    config('fastbooking.tour_package_image.original'),
                    'public',
                    true,
                    200
                ), function ($value) use ($tourPackage) {

                    Storage::disk('public')->delete(substr($tourPackage->feature_image, strlen(config('fastbooking.tour_package_image.base_path'))));

                    return $value['file_name'];
//                $feature_image =  config('fastbooking.tour_package_image.base_path').config('fastbooking.tour_package_image.original').$value['file_name'];
                });
                if (!$feature_image) {
                    throw new \Exception('File was too big');
                }
            }

            $gallery_images = [];
            if(array_key_exists('gallery_images', $validated)){
                foreach ($validated['gallery_images'] as $gallery) {
                    $uploaded         = $this->uploadSingleImage($gallery, config('fastbooking.tour_package_image.original'), 'public', true);
                    $gallery_images[] = [
                        'original' => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $uploaded['file_name'],
                        'thumb'    => config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.thumb') . $uploaded['file_name'],
                    ];
                }
            }

            $count_gallery_image = count($gallery_images);
            $tourPackage->update([
                'title'         => $validated['package_title'],
                'place_name'    => $validated['place_name'],
                'duration'      => $validated['duration'],
                'descriptions'  => $validated['descriptions'],
                'category_id'   => $validated['category_id'],
                'feature_image' => array_key_exists('file_name', $feature_image) ? config('fastbooking.tour_package_image.base_path') . config('fastbooking.tour_package_image.original') . $feature_image['file_name'] : $tourPackage->feature_image,
//                'gallery'       => $count_gallery_image ? json_encode(array_merge(json_decode($tourPackage->gallery, true), $gallery_images), true) : $tourPackage->gallery,
            ]);
            if ($count_gallery_image > 0){
                foreach ($gallery_images as $gallery_image){
                    TourPackageImage::create([
                        'tour_package_id' => $tourPackage->id,
                        'original' => $gallery_image['original'],
                        'thumbnail' => $gallery_image['thumb'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            return response()->json('update successful', 204);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (QueryException | \Exception $e) {
            Log::info('update error', [
                    'message' => $e->getMessage(),
                    'filename' => $e->getFile(),
                    'line no' => $e->getLine()
            ]);
            return response()->json('Something went wrong', 406);
        }
    }

    public function imageRemoveFromGallery(Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validate($request, [
                'image_id' => 'required|integer|exists:tour_package_images,id'
            ]);
            $tour_package_image = TourPackageImage::find($validatedData['image_id']);
            $package_id = $tour_package_image->tour_package_id;
            TourPackageImage::destroy($validatedData['image_id']);
            $images = TourPackage::where('id', $package_id)->with([
                'category' => function ($category) {
                    $category->select('id', 'name');
                }, 'images'
            ])->orderBy('place_name', 'asc')->first();
            return response()->json(['images' => $images]);
        }catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        }catch (QueryException|\Exception $e){
            return response()->json('Something went wrong', 406);
        }

    }
    // Remove image from tour package table gallery column
    public function imageRemove(Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validate($request, [
               'package_id' => 'required|integer|exists:tour_packages,id',
               'image' => 'required|string|max:255'
            ]);

            $package = TourPackage::find($validatedData['package_id']);
            $gallery_images = json_decode($package->gallery, true);
            foreach ($gallery_images as $key => $image){
                if ($validatedData['image'] == $image['original']){
                    unset($gallery_images[$key]);
                    break;
                }
            }
            $package->update([
                'gallery' => json_encode($gallery_images, true)
            ]);
            $package->gallery = json_decode($package->gallery, true);
            return response()->json($package, 200);
        }catch (ValidationException $e){
            return response()->json($e->errors(), 422);
        } catch (QueryException | \Exception $e) {
            Log::info('update error', [
                'message' => $e->getMessage(),
                'filename' => $e->getFile(),
                'line no' => $e->getLine()
            ]);
            return response()->json('Something went wrong!', 406);
        }

    }

    public function delete(Request $request)
    {
        try{
            $ids = $this->validate($request, [
               'packages' => 'required|array|min:1',
               'packages.*' => 'required|integer|exists:tour_packages,id',
            ]);
            TourPackage::destroy($ids['packages']);
            return response()->json('deleted');
        }catch (ValidationException $e){
            return response()->json($e->errors(), 422);
        }

    }

}


