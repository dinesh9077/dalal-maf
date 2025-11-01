<?php

	namespace App\Models;

use App\Models\Property\Property;
use App\Models\Property\PrtFloorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use App\Models\User;

	class SaleInvoiceItem extends Model
	{
		use HasFactory;

		protected static $recordEvents = ['created', 'deleted', 'updated'];


		public function user()
		{
			return $this->belongsTo(User::class, 'user_id');
		}

		public function vendor()
		{
			return $this->belongsTo(Vendor::class, 'vendor_id');
		}

		public function invoice()
		{
			return $this->belongsTo(SaleInvoice::class, 'invoice_id');
		}

    public function property()
    {
      return $this->belongsTo(Property::class, 'name');
    }


	}
