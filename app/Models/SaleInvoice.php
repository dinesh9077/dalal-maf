<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use App\Models\User;


	class SaleInvoice extends Model
	{
		use HasFactory;

		protected static $recordEvents = ['created', 'deleted', 'updated'];

		protected static function boot()
		{
			parent::boot();

			static::deleting(function ($model)
			{
				// Check if related records exist
				$dependencies = [
					SaleInvoiceBilling::where("invoice_id", $model->id)
				];

				foreach ($dependencies as $query) {
					if ($query->exists()) {
						// Prevent deletion by throwing an exception or handling it
						throw new \Exception(trans('custom.delete_msg'));
					}
				}


				// Define related models and the foreign key columns
				$dependencies = [
					\App\Models\SaleInvoiceItem::class => 'invoice_id',
					\App\Models\SaleInvoiceBilling::class => 'invoice_id',
				];

				foreach ($dependencies as $modelClass => $foreignKey)
				{
					// Get the model class instance and find related records
					$query = $modelClass::where($foreignKey, $model->id);
					// Delete related records from the database
					$query->delete();
				}
			});
		}

		public function sale_user()
		{
			return $this->belongsTo(User::class, 'user_id');
		}

		public function vendor()
		{
			return $this->belongsTo(Vendor::class, 'vendor_id');
		}

		public function saleInvoiceBillings()
		{
			return $this->hasMany(SaleInvoiceBilling::class, 'invoice_id');
		}

		public function customer()
		{
			return $this->belongsTo(Customer::class, 'customer_id');
		}

	}
