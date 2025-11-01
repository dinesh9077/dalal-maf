<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';
    protected $guarded = [];

    public function unitContent()
    {
        return $this->hasOne(PropertyUnit::class, 'unit_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany(PropertyUnit::class, 'unit_id', 'id');
    }

}
