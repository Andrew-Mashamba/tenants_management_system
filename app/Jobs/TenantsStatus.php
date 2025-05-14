<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Lease;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class TenantsStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //change tenants status based on lease status
        Log::info('Starting Tenants Status Update');
        
        $today = Carbon::now();
        $leases = Lease::all();
        Log::info('Leases: ' . $leases->count());
        try{
            foreach ($leases as $lease) {
                $start_date = Carbon::parse($lease->start_date);
                $end_date = Carbon::parse($lease->end_date);
                if($today->between($start_date, $end_date)){
                    Log::info('Lease: ' . $lease->id . ' is active');
                    $lease->status = 'active';
                    $lease->tenant->status = $lease->status;
                    $lease->tenant->save();
                }
                // check if lease is expired and if is greater than two days terminate the lease
                if($today->gt($end_date)){
                    Log::info('Lease: ' . $lease->id . ' is expired');
                    $lease->status = 'expired';
                    $lease->tenant->status = $lease->status;
                    $lease->tenant->save();
                }
                if($today->gt($end_date->addDays(2))){
                    Log::info('Lease: ' . $lease->id . ' is terminated');
                    $lease->status = 'terminated';
                    $lease->tenant->status = $lease->status;
                    $lease->tenant->save();
                }
                if($today->lt($start_date)){
                    Log::info('Lease: ' . $lease->id . ' is pending');
                    $lease->status = 'pending';
                    // $lease->tenant->status = $lease->status;
                    // $lease->tenant->save();
                }
                $lease->save();
            }
            Log::info('Tenants Status Update Completed');
        } catch (\Exception $e) {
            Log::error('Error updating tenants status: ' . $e->getMessage());
        }
        
    }
}
