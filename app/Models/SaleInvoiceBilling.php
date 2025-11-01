<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use App\Models\User;
	class SaleInvoiceBilling extends Model
	{
		use HasFactory;

		protected static $recordEvents = ['created', 'deleted', 'updated'];


		// public function sale_user()
		// {
		// 	return $this->belongsTo(User::class, 'sale_user_id');
		// }

		public function vendor()
		{
			return $this->belongsTo(Vendor::class, 'vendor_id');
		}
		public function customer()
		{
			return $this->belongsTo(Customer::class, 'customer_id');
		}
		public function invoice()
		{
			return $this->belongsTo(SaleInvoice::class, 'invoice_id');
		}
		// public function bank()
		// {
		// 	return $this->belongsTo(AccBankCash::class, 'bank_id');
		// }
	}
