<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    use HasFactory;
    protected $table = 'property_units';

    protected $fillable = [
      'property_id',
      'unit_id'
    ];

    public function amenity()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
