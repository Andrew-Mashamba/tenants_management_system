<?php

namespace App\Http\Controllers\Leases;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\LeaseWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaseWorkflowController extends Controller
{
    public function approve(Request $request, Lease $lease, LeaseWorkflow $workflow)
    {
        $workflow->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('leases.workflows.edit', [$lease, $workflow])
            ->with('success', 'Workflow approved successfully.');
    }

    public function reject(Request $request, Lease $lease, LeaseWorkflow $workflow)
    {
        $workflow->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->input('reason'),
        ]);

        return redirect()->route('leases.workflows.edit', [$lease, $workflow])
            ->with('success', 'Workflow rejected successfully.');
    }
} 