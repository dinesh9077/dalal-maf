<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryContent extends Model
{
    use HasFactory;

    protected $table = 'property_inventory_contents';
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(PropertyInventory::class, 'property_id', 'id');
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
        return $this->hasMany(PropertyInventorySliderImage::class, 'property_id', 'property_id');
    }
}

