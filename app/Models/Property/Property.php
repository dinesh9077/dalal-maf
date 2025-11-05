<?php

namespace App\Models\Property;

use App\Models\Agent;
use App\Models\Amenity;
use App\Models\BasicSettings\Basic;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

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

    protected function isCountryActive(): Attribute
    {
        return Attribute::make(
            get: function ($value) {

                $county_status = $this->basicInfo->property_country_status; // Change this to match your attribute name

                $attributeName = 'country_id';
                // Check if the attribute exists and is not null
                if ($this->attributes[$attributeName] && $county_status == 1) {

                    return true;
                }

                return false; // Return false if the attribute is null or doesn't exist
            }
        );
    }

    protected function isStateActive(): Attribute
    {
        return Attribute::make(
            get: function ($value) {

                $county_status = $this->basicInfo->property_state_status; // Change this to match your attribute name

                $attributeName = 'state_id';
                // Check if the attribute exists and is not null
                if ($this->attributes[$attributeName] && $county_status == 1) {

                    return true;
                }

                return false; // Return false if the attribute is null or doesn't exist
            },
        );
    }


    public  function basicInfo(): Attribute
    {
        return Attribute::make(
            get: function ($value) {

                return Basic::first();
            },
        );
    }
    public function propertyContent()
    {
        return $this->hasOne(Content::class);
    }

    public function propertyContents()
    {
        return $this->hasMany(Content::class, 'property_id', 'id');
    }

    public function units()
    {
       return $this->hasMany(PropertyUnit::class, 'property_id', 'id');
    }

    public function getContent($lanId)
    {
        return $this->propertyContents()->where('language_id', $lanId)->first();
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', "id");
    }

    public function featuredProperties()
    {
        return $this->hasMany(FeaturedProperty::class, 'property_id', 'id');
    }

    public function specifications()
    {
        return $this->hasMany(Spacification::class, 'property_id', 'id');
    }

    public function galleryImages()
    {
        return $this->hasMany(PropertySliderImage::class, 'property_id', 'id');
    }

    public function proertyAmenities()
    {
        return $this->hasMany(PropertyAmenity::class, 'property_id', 'id');
    }

    public function proertyUnits()
    {
        return $this->hasMany(PropertyUnit::class, 'property_id', 'id');
    }

    public function propertyCity()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function cityContent()
    {
        return $this->belongsTo(CityContent::class, 'city_id', 'city_id');
    }
    public function areaContent()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function categoryContent()
    {
        return $this->belongsTo(PropertyCategoryContent::class, 'category_id', 'category_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'property_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function propertyMessages(){
        return $this->hasMany(PropertyContact::class, 'property_id', 'id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities')
                    ->withTimestamps();
    }

    public function updateunits()
    {
        return $this->belongsToMany(Unit::class, 'property_units')
                    ->withTimestamps();
    }
	
	
	public function scopeActive($q) { return $q->where('status', 1); }
	public function scopeApproved($q) { return $q->where('approve_status', 1); }
	public function scopeWithLanguage($q, $langId) {
		return $q->join('property_contents', 'property_contents.property_id', '=', 'properties.id')
				 ->where('property_contents.language_id', $langId);
	}
	public function scopeWithMembership($q) {
		return $q->leftJoin('memberships', 'properties.vendor_id', '=', 'memberships.vendor_id');
	}
	public function scopeWithVendorStatus($q) {
		return $q->leftJoin('vendors', 'properties.vendor_id', '=', 'vendors.id')
				 ->where(function ($q) {
					 $q->where('vendors.status', 1)->orWhere('properties.vendor_id', 0);
				 });
	}

}
