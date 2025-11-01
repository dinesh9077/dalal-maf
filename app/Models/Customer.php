<?php

namespace App\Models;

use App\Models\Property\PrtFloorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone_number','vendor_id','agent_id'];

    public function prtFloorStatus()
		{
			return $this->belongsTo(PrtFloorStatus::class, 'id');
		}
}
