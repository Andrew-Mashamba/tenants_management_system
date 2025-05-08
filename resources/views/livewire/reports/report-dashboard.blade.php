<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Property Selector -->
            <div>
                <label for="property" class="block text-sm font-medium text-gray-700">Property</label>
                <select wire:model.live="selectedProperty" id="property" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Property</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range Selector -->
            <div>
                <label for="dateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
                <select wire:model.live="dateRange" id="dateRange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>

            <!-- Custom Date Range -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" wire:model.live="startDate" id="startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" wire:model.live="endDate" id="endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </div>
    </div>

    @if($selectedProperty)
        <!-- Occupancy Report -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Occupancy Report</h3>
            @if($occupancyReport)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Units</p>
                        <p class="text-2xl font-semibold">{{ $occupancyReport->total_units }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Occupied Units</p>
                        <p class="text-2xl font-semibold">{{ $occupancyReport->occupied_units }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Vacant Units</p>
                        <p class="text-2xl font-semibold">{{ $occupancyReport->vacant_units }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Occupancy Rate</p>
                        <p class="text-2xl font-semibold">{{ number_format($occupancyReport->occupancy_rate, 1) }}%</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No occupancy data available for the selected period.</p>
            @endif
        </div>

        <!-- Rent Collection Report -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rent Collection Report</h3>
            @if($rentCollectionReport)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Rent Due</p>
                        <p class="text-2xl font-semibold">${{ number_format($rentCollectionReport->total_rent_due, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Collected</p>
                        <p class="text-2xl font-semibold">${{ number_format($rentCollectionReport->total_rent_collected, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Outstanding</p>
                        <p class="text-2xl font-semibold">${{ number_format($rentCollectionReport->total_outstanding, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Collection Rate</p>
                        <p class="text-2xl font-semibold">{{ $rentCollectionReport->total_rent_due > 0 ? number_format(($rentCollectionReport->total_rent_collected / $rentCollectionReport->total_rent_due) * 100, 1) : 0 }}%</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No rent collection data available for the selected period.</p>
            @endif
        </div>

        <!-- Maintenance Report -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Report</h3>
            @if($maintenanceReport)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Requests</p>
                        <p class="text-2xl font-semibold">{{ $maintenanceReport->total_requests }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold">{{ $maintenanceReport->completed_requests }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold">{{ $maintenanceReport->pending_requests }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Cost</p>
                        <p class="text-2xl font-semibold">${{ number_format($maintenanceReport->total_cost, 2) }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No maintenance data available for the selected period.</p>
            @endif
        </div>

        <!-- Lease Expiry Report -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Lease Expiry Report</h3>
            @if($leaseExpiryReport)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Expiring This Month</p>
                        <p class="text-2xl font-semibold">{{ $leaseExpiryReport->expiring_this_month }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Expiring Next Month</p>
                        <p class="text-2xl font-semibold">{{ $leaseExpiryReport->expiring_next_month }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Expiring in 3 Months</p>
                        <p class="text-2xl font-semibold">{{ $leaseExpiryReport->expiring_in_three_months }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No lease expiry data available for the selected period.</p>
            @endif
        </div>

        <!-- Financial Dashboard -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Dashboard</h3>
            @if($financialMetrics)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-semibold">${{ number_format($financialMetrics->total_revenue, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Total Expenses</p>
                        <p class="text-2xl font-semibold">${{ number_format($financialMetrics->total_expenses, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Net Income</p>
                        <p class="text-2xl font-semibold">${{ number_format($financialMetrics->net_income, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Operating Expenses</p>
                        <p class="text-2xl font-semibold">${{ number_format($financialMetrics->operating_expenses, 2) }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No financial data available for the selected period.</p>
            @endif
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-gray-500">Please select a property to view reports.</p>
        </div>
    @endif
</div> 