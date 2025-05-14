<div>
    <div class="mb-4 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" wire:model.live="search" placeholder="Search by tenant name or unit number..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex gap-4">
            <select wire:model.live="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="expired">Expired</option>
                <option value="terminated">Terminated</option>
                <option value="pending">Pending</option>
            </select>
            <select wire:model.live="payment_frequency" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Frequencies</option>
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="annually">Annually</option>
            </select>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($leases as $lease)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($lease->status === 'active') bg-green-100 text-green-800
                                        @elseif($lease->status === 'expired') bg-gray-100 text-gray-800
                                        @elseif($lease->status === 'terminated') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($lease->status) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-indigo-600">
                                        {{ $lease->tenant?->name ?? 'No Tenant Assigned' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Unit {{ $lease->unit?->unit_number ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    ${{ number_format($lease->rent_amount, 2) }}
                                    <span class="text-xs text-gray-500">/{{ $lease->payment_frequency }}</span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lease->payment_frequency }} payments
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <a href="{{ route('leases.show', $lease) }}" class="text-indigo-600 hover:text-indigo-900">
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
        {{ $leases->links() }}
    </div>
</div>
