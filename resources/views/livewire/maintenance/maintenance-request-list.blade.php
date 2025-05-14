<div>
    <div class="mb-4 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" wire:model.live="search" placeholder="Search by title, description, or tenant..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex gap-4">
            <select wire:model.live="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select wire:model.live="priority" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
            </select>
            <select wire:model.live="property_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Properties</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="start_date" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <input type="date" wire:model.live="end_date" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($maintenanceRequests as $request)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($request->priority === 'urgent') bg-red-100 text-red-800
                                        @elseif($request->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($request->priority) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-indigo-600">
                                        {{ $request->title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $request->property->name }} - Unit {{ $request->unit->unit_number }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $request->tenant?->name ?? 'Unassigned With Tenant' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $request->requested_date->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ ucwords(str_replace('_', ' ', $request->status)) }}
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <a href="{{ route('maintenance.requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-4">
        {{ $maintenanceRequests->links() }}
    </div>
</div>
