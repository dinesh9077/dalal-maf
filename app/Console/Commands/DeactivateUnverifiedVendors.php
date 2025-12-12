<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vendor;
use Carbon\Carbon;

class DeactivateUnverifiedVendors extends Command
{
    protected $signature = 'vendors:deactivate-unverified';

    protected $description = 'Deactivate vendors whose KYC is pending for more than 7 days';

    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(7);

        $vendors = Vendor::where('is_kyc_approved', "0")
            ->where('status', 1)   // active
            ->whereDate('created_at', '<=', $cutoffDate)
            ->get();
       
        $count = $vendors->count();

        foreach ($vendors as $vendor) {
            $vendor->update([
                'status' => 0,   // deactivate vendor
            ]);
        }

        $this->info("Total {$count} vendors deactivated successfully.");

        return 0;
    }
}
