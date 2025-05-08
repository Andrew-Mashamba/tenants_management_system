<?php

namespace App\Http\Controllers;

use App\Models\AccountingIntegration;
use App\Models\Property;
use App\Models\ChartOfAccountsMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccountingIntegrationController extends Controller
{
    public function index(Property $property)
    {
        Gate::authorize('view', $property);
        
        $integrations = $property->accountingIntegrations()
            ->with('chartOfAccountsMappings')
            ->get();
            
        return view('accounting.integrations.index', compact('property', 'integrations'));
    }

    public function create(Property $property)
    {
        Gate::authorize('update', $property);
        
        return view('accounting.integrations.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        Gate::authorize('update', $property);
        
        $validated = $request->validate([
            'provider' => ['required', 'in:quickbooks,xero'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'environment' => ['required', 'in:production,development'],
        ]);
        
        $integration = $property->accountingIntegrations()->create([
            'provider' => $validated['provider'],
            'credentials' => [
                'client_id' => $validated['client_id'],
                'client_secret' => $validated['client_secret'],
                'environment' => $validated['environment'],
            ],
            'is_active' => false,
        ]);
        
        $service = $integration->getService();
        $authUrl = $service->getAuthUrl();
        
        return redirect($authUrl);
    }

    public function callback(Request $request, Property $property)
    {
        Gate::authorize('update', $property);
        
        $integration = $property->accountingIntegrations()
            ->where('is_active', false)
            ->latest()
            ->firstOrFail();
            
        $service = $integration->getService();
        
        if ($service->handleCallback($request->code)) {
            $service->syncAccounts();
            
            return redirect()
                ->route('accounting.integrations.index', $property)
                ->with('success', 'Accounting integration has been successfully connected.');
        }
        
        return redirect()
            ->route('accounting.integrations.index', $property)
            ->with('error', 'Failed to connect accounting integration.');
    }

    public function destroy(Property $property, AccountingIntegration $integration)
    {
        Gate::authorize('update', $property);
        
        $integration->delete();
        
        return redirect()
            ->route('accounting.integrations.index', $property)
            ->with('success', 'Accounting integration has been successfully removed.');
    }

    public function sync(Property $property, AccountingIntegration $integration)
    {
        Gate::authorize('update', $property);
        
        $service = $integration->getService();
        
        if ($service->syncAccounts()) {
            $integration->update(['last_sync_at' => now()]);
            
            return redirect()
                ->route('accounting.integrations.index', $property)
                ->with('success', 'Chart of accounts has been successfully synchronized.');
        }
        
        return redirect()
            ->route('accounting.integrations.index', $property)
            ->with('error', 'Failed to synchronize chart of accounts.');
    }

    public function mappings(Property $property, AccountingIntegration $integration)
    {
        $this->authorize('view', $property);
        $this->authorize('view', $integration);

        $mappings = $integration->chartOfAccountsMappings()
            ->orderBy('provider_account_name')
            ->get();

        return view('accounting.integrations.mappings', compact('property', 'integration', 'mappings'));
    }

    public function updateMapping(Request $request, Property $property, AccountingIntegration $integration, ChartOfAccountsMapping $mapping)
    {
        $this->authorize('update', $property);
        $this->authorize('update', $integration);

        $validated = $request->validate([
            'system_account' => ['required', 'string', 'in:bank,accounts_receivable,current_asset,fixed_asset,other_asset,accounts_payable,credit_card,current_liability,long_term_liability,equity,income,cost_of_goods_sold,expense,other_income,other_expense'],
        ]);

        $mapping->update($validated);

        return redirect()
            ->route('accounting.integrations.mappings', [$property, $integration])
            ->with('success', 'Account mapping updated successfully.');
    }

    public function destroyMapping(Property $property, AccountingIntegration $integration, ChartOfAccountsMapping $mapping)
    {
        $this->authorize('update', $property);
        $this->authorize('update', $integration);

        $mapping->delete();

        return redirect()
            ->route('accounting.integrations.mappings', [$property, $integration])
            ->with('success', 'Account mapping removed successfully.');
    }
} 