<?php

namespace App\Models\Property;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

	class PrtFloorStatus extends Model
	{
		use HasFactory;

    protected $table = 'prt_floor_status';
    protected $fillable = ['customer_id', 'property_id', 'wing_id','floor_id', 'unit_id','status'];

    public function customer()
		{
			return $this->belongsTo(Customer::class, 'customer_id');
		}

    public function property()
		{
			return $this->belongsTo(Property::class, 'property_id');
		}

    public function wing()
		{
			return $this->belongsTo(PrtWing::class, 'wing_id');
		}

		public function floors()
		{
			return $this->belongsTo(PrtFloor::class, 'floor_id');
		}

    public function Units()
		{
			return $this->belongsTo(PrtUnit::class, 'unit_id');
		}



	}
