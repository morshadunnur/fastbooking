<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoreActivity extends Model
{
    use HasFactory;
    protected $table = 'bore_activities';
    protected $guarded = [];
    protected $casts = [
        'api_response' => 'array'
    ];
}
