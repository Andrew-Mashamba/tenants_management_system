<div>
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ $workflow->exists ? 'Update' : 'Initiate' }} {{ ucfirst($type) }} Workflow
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $type === 'renewal' ? 'Initiate the lease renewal process.' : 'Initiate the lease termination process.' }}
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="type" class="block text-sm font-medium text-gray-700">Workflow Type</label>
                            <select wire:model="type" id="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="renewal">Renewal</option>
                                <option value="termination">Termination</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="effective_date" class="block text-sm font-medium text-gray-700">Effective Date</label>
                            <input type="date" wire:model="effective_date" id="effective_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('effective_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if($type === 'renewal')
                            <div class="col-span-6 sm:col-span-3">
                                <label for="new_expiry_date" class="block text-sm font-medium text-gray-700">New Expiry Date</label>
                                <input type="date" wire:model="new_expiry_date" id="new_expiry_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('new_expiry_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="col-span-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <textarea wire:model="reason" id="reason" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ $template ? 'Update Template' : 'Create Template' }}
            </button>
        </div>
    </form>
</div>

<livewire:leases.lease-document-form :lease="$lease" :document="$document" /> 