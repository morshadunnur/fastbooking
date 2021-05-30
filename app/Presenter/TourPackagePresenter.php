<?php
/**
 * @author Author Name: Md Morshadun Nur
 * @email  Email: morshadunnur@gmail.com
 */

namespace App\Presenter;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class TourPackagePresenter
{
    public static function fetchList(Collection $tour_packages): JsonResponse
    {
        $tour_packages = $tour_packages->transform(function ($tour_package){
            $tour_package->gallery = json_decode($tour_package->gallery, true);
            return $tour_package;
        });
        return response()->json([
            'packages' => $tour_packages
        ], 200);
    }
}
