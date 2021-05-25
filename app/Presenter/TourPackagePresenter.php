<?php
/**
 * @author Author Name: Md Morshadun Nur
 * @email  Email: morshadunnur@gmail.com
 */

namespace App\Presenter;


use Illuminate\Http\JsonResponse;

class TourPackagePresenter
{
    public static function fetchList($tour_packages): JsonResponse
    {
        return response()->json([
            'packages' => $tour_packages
        ], 200);
    }
}
