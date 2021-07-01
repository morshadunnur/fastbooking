<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tour_packages';
    protected $guarded = [];

//    protected $casts = [
//      'gallery' => 'array'
//    ];



    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function savePackage($data)
    {

    }

    public function images(): HasMany
    {
        return $this->hasMany(TourPackageImage::class, 'tour_package_id');
    }

}
