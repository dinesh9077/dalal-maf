<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'property_contents';
    protected $guarded = [];
 
    protected $appends = [
        'featured_image_url',
        'floor_planning_image_url',
        'video_image_url',
    ];
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? url('/') . '/assets/img/property/featureds/' . $this->featured_image : null;
    } 

    public function getFloorPlanningImageUrlAttribute()
    {
        return $this->floor_planning_image ? url('/') . '/assets/img/property/plannings/' . $this->floor_planning_image : null;
    } 

    public function getVideoImageUrlAttribute()
    {
        return $this->video_image ? url('/') . '/assets/img/property/video/' . $this->video_image : null;
    }


    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function categoryContent()
    {
        return $this->belongsTo(PropertyCategoryContent::class, 'category_id', 'category_id');
    }

    public function propertySpacifications()
    {
        return $this->hasMany(Spacification::class, 'property_id', 'property_id');
    }
    public function galleryImages()
    {
        return $this->hasMany(PropertySliderImage::class, 'property_id', 'property_id');
    }
}
