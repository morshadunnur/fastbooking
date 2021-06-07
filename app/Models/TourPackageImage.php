<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPackageImage extends Model
{
    use HasFactory;
    protected $table = 'tour_package_images';
    protected $guarded = [];
    protected $hidden = [
      'created_at', 'updated_at'
    ];
    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'tour_package_id', 'id');
    }
}
