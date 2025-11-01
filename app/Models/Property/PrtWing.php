<?php

namespace App\Models\Property;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtWing extends Model
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
}
