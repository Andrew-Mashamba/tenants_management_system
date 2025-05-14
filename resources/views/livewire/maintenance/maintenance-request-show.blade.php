<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Maintenance Request Details</h2>
        <div class="space-x-2">
            <a href="{{ route('maintenance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <a href="{{ route('maintenance.requests.edit', $maintenanceRequest) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4">Request Information</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Title</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Priority</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($maintenanceRequest->priority === 'emergency') bg-red-100 text-red-800
                                @elseif($maintenanceRequest->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($maintenanceRequest->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($maintenanceRequest->priority) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($maintenanceRequest->status === 'completed') bg-green-100 text-green-800
                                @elseif($maintenanceRequest->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($maintenanceRequest->status === 'assigned') bg-purple-100 text-purple-800
                                @elseif($maintenanceRequest->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $maintenanceRequest->status)) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Additional Details</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Property</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->property->name }}</dd>
                    </div>
                    @if($maintenanceRequest->unit)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->unit->name }}</dd>
                    </div>
                    @endif
                    @if($maintenanceRequest->tenant)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tenant</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->tenant->name }}</dd>
                    </div>
                    @endif
                    @if($maintenanceRequest->vendor)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned Vendor</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->vendor->name }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Requested Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->requested_date?->format('M d, Y H:i') }}</dd>
                    </div>
                    @if($maintenanceRequest->scheduled_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Scheduled Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->scheduled_date->format('M d, Y H:i') }}</dd>
                    </div>
                    @endif
                    @if($maintenanceRequest->estimated_cost)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estimated Cost</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($maintenanceRequest->estimated_cost, 2) }}</dd>
                    </div>
                    @endif
                    @if($maintenanceRequest->actual_cost)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Actual Cost</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($maintenanceRequest->actual_cost, 2) }}</dd>
                    </div>
                    @endif
                    @if($maintenanceRequest->assignedTo)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->assignedTo->name }}</dd>
                    </div>
                    @endif
                    @if($maintenanceRequest->notes)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $maintenanceRequest->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        @if($maintenanceRequest->resolution_notes)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Resolution Notes</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-900">{{ $maintenanceRequest->resolution_notes }}</p>
            </div>
        </div>
        @endif

        @if($maintenanceRequest->attachments && count($maintenanceRequest->attachments) > 0)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Attachments</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($maintenanceRequest->attachments as $attachment)
                <div class="bg-gray-50 rounded-lg p-4">
                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        {{ basename($attachment) }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
