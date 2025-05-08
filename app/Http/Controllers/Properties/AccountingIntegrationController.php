<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\AccountingIntegration;
use Illuminate\Http\Request;

class AccountingIntegrationController extends Controller
{
    public function index(Property $property)
    {
        $integrations = $property->accountingIntegrations()->latest()->get();
        return view('properties.accounting.integrations.index', compact('property', 'integrations'));
    }

    public function create(Property $property)
    {
        return view('properties.accounting.integrations.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'provider' => 'required|string|in:quickbooks',
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $property->accountingIntegrations()->create($validated);

        return redirect()->route('accounting.integrations.index', $property)
            ->with('success', 'Integration created successfully.');
    }

    public function destroy(Property $property, AccountingIntegration $integration)
    {
        $integration->delete();
        return redirect()->route('accounting.integrations.index', $property)
            ->with('success', 'Integration deleted successfully.');
    }

    public function sync(Property $property, AccountingIntegration $integration)
    {
        // Implement sync logic here
        return redirect()->route('accounting.integrations.index', $property)
            ->with('success', 'Integration synced successfully.');
    }

    public function callback(Request $request, Property $property)
    {
        // Handle OAuth callback
        return redirect()->route('accounting.integrations.index', $property)
            ->with('success', 'Integration connected successfully.');
    }
} 