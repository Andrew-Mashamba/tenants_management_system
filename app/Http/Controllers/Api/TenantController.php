<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TenantController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tenants = Tenant::whereHas('property', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['property', 'documents', 'verifications'])->get();

        return response()->json($tenants);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants',
            'phone' => 'required|string',
            'move_in_date' => 'required|date',
            'lease_end_date' => 'required|date|after:move_in_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $property = Property::findOrFail($request->property_id);
        $this->authorize('update', $property);

        $tenant = Tenant::create([
            'property_id' => $property->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'move_in_date' => $request->move_in_date,
            'lease_end_date' => $request->lease_end_date,
        ]);

        return response()->json($tenant, 201);
    }

    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);
        return response()->json($tenant->load(['property', 'documents', 'verifications']));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'sometimes|required|string',
            'move_in_date' => 'sometimes|required|date',
            'lease_end_date' => 'sometimes|required|date|after:move_in_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tenant->update($request->all());
        return response()->json($tenant);
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorize('delete', $tenant);
        $tenant->delete();
        return response()->json(null, 204);
    }
} 