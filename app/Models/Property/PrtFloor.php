<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Property\Property;
use App\Models\Property\PrtWing;

	class PrtFloor extends Model
	{
		use HasFactory;

		protected static $recordEvents = ['created', 'deleted', 'updated'];


		public function user()
		{
			return $this->belongsTo(User::class, 'user_id');
		}

		public function property()
		{
			return $this->belongsTo(Property::class, 'property_id');
		}

		public function units()
		{
			return $this->hasMany(PrtUnit::class, 'floor_id');
		}

		public function wing()
		{
			return $this->belongsTo(PrtWing::class, 'wing_id');
		}
	}
