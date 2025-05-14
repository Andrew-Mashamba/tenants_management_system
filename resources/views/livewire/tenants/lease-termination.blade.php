<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">
                    Terminate Lease
                </h3>
                <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                    Warning: This action cannot be undone
                </span>
            </div>

            <!-- Lease Information Card -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Lease Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tenant</p>
                        <p class="text-sm text-gray-900">{{ $lease->tenant->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Property</p>
                        <p class="text-sm text-gray-900">{{ $lease->property->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Units</p>
                        <div class="text-sm text-gray-900">
                            @foreach($lease->unit_ids as $unitId)
                                @php
                                    $unit = $units->firstWhere('id', $unitId);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-1">
                                    {{ $unit->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Lease Period</p>
                        <p class="text-sm text-gray-900">
                            {{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Termination Form -->
            <div class="mt-6">
                <form wire:submit.prevent="confirmTermination" class="space-y-6">
                    <div>
                        <label for="terminationDate" class="block text-sm font-medium text-gray-700">Termination Date</label>
                        <div class="mt-1">
                            <input type="date" 
                                   wire:model="terminationDate" 
                                   id="terminationDate" 
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                   min="{{ now()->format('Y-m-d') }}">
                        </div>
                        @error('terminationDate') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Termination</label>
                        <div class="mt-1">
                            <textarea wire:model="reason" 
                                      id="reason" 
                                      rows="4" 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                      placeholder="Please provide a detailed reason for terminating this lease..."></textarea>
                        </div>
                        @error('reason') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('leases.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Terminate Lease
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
