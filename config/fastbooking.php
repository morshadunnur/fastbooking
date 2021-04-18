<?php
/**
 * @author Author Name: Md Morshadun Nur
 * @email  Email: morshadunnur@gmail.com
 */
return [
      'tour_package_image' => [
          'base_path' => env('APP_URL', 'http://fastbooking.test/'),
          'original' => env('TOUR_PACKAGE_ORIGINAL_IMAGE_PATH', '/tour_packages'),
          'thumb' => env('TOUR_PACKAGE_THUMBNAIL_IMAGE_PATH', '/resized')
      ]
];
