<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use URL;

class PropertySliderImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image',
    ];
    
    protected $appends = ['full_image_url'];

    public function getFullImageUrlAttribute()
    {
        return URL::to('/') . '/assets/img/property/slider-images/' . $this->image;
    }
}
