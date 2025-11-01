<?php

	namespace App\Models\Property;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Spatie\Activitylog\Traits\LogsActivity;
	use Spatie\Activitylog\LogOptions;
	use App\Models\MstLeadSource;
	use App\Models\User;
	use App\Models\MstCity;
	use App\Models\MstCategory;
	use App\Models\PrtProperty;
	use App\Models\MstBudget;
	use App\Models\LdLead;
	use App\Models\MstUnitType;
	use App\Models\MstFurnishing;
use App\Models\Property\PrtFloor as PropertyPrtFloor;
use App\Models\Property\PrtWing as PropertyPrtWing;
use App\Models\PrtWing;
	use App\Models\PrtFloor;

	class PrtUnit extends Model
	{
		use HasFactory;

		protected static $recordEvents = ['created', 'deleted', 'updated'];

		 protected $fillable = [
			'user_id',
			'property_id',
			'category_id',
			'unit_type',
			'sqft',
			'budget',
			'price',
			'furnishing_id',
			'no_of_bathroom',
			'no_of_balcony',
			'no_of_bedroom',
			'no_of_floor',
			'property_age',
			'facing',
			'bachelor_allow',
			'remark',
			'status',
			'property_status',
			'wing_id',
			'floor_id',
			'flat_no',
			'plot_size',
			'flat_number',
			'property_work',
			'created_at',
			'updated_at',
		];

		public function user()
		{
			return $this->belongsTo(User::class, 'user_id');
		}

		// public function budgets()
		// {
		// 	return $this->belongsTo(MstBudget::class, 'budget');
		// }

		// public function category()
		// {
		// 	return $this->belongsTo(MstCategory::class, 'category_id');
		// }
		public function property()
		{
			return $this->belongsTo(Property::class, 'property_id');
		}

		public function unit()
		{
			return $this->belongsTo(Unit::class, 'unit_type');
		}

		// public function city()
		// {
		// 	return $this->belongsTo(MstCity::class, 'city_id');
		// }
		// public function furnished()
		// {
		// 	return $this->belongsTo(MstFurnishing::class, 'furnishing_id');
		// }

		public function wing()
		{
			return $this->belongsTo(PropertyPrtWing::class, 'wing_id');
		}

		public function floors()
		{
			return $this->belongsTo(PropertyPrtFloor::class, 'floor_id');
		}


		// public function soldby()
		// {
		// 	return $this->hasOne(PrtSold::class, 'unit_id')->whereStatus(0);
		// }
	}
