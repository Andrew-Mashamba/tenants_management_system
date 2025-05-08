<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VerificationService;
use App\Models\TenantVerification;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $verifications = TenantVerification::whereHas('tenant', function ($query) {
            $query->where('property_id', Auth::user()->property_id);
        })->with(['tenant', 'verificationService'])->get();

        return response()->json($verifications);
    }

    public function show(TenantVerification $verification)
    {
        $this->authorize('view', $verification);
        return response()->json($verification->load(['tenant', 'verificationService']));
    }

    public function verify(Request $request, TenantVerification $verification)
    {
        $this->authorize('update', $verification);

        $validator = Validator::make($request->all(), [
            'verification_type' => 'required|string|in:id,credit',
            'data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = VerificationService::where('property_id', Auth::user()->property_id)
            ->where('service_type', $request->verification_type)
            ->where('is_active', true)
            ->firstOrFail();

        $result = $service->verifyTenant($verification->tenant, $request->verification_type);

        $verification->update([
            'verification_data' => $result['data'],
            'status' => $result['status'],
            'verified_at' => now(),
        ]);

        return response()->json($verification);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'verification_type' => 'required|string|in:id,credit',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tenant = Tenant::findOrFail($request->tenant_id);
        $service = VerificationService::where('property_id', Auth::user()->property_id)
            ->where('service_type', $request->verification_type)
            ->where('is_active', true)
            ->firstOrFail();

        $verification = TenantVerification::create([
            'tenant_id' => $tenant->id,
            'verification_service_id' => $service->id,
            'verification_type' => $request->verification_type,
            'status' => 'pending',
        ]);

        return response()->json($verification, 201);
    }
} 